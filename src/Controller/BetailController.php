<?php

namespace App\Controller;

use App\Entity\Betail;
use App\Entity\Ferme;
use App\Entity\Zone;
use App\Entity\FamilleBetail;
use App\Entity\FamilleBetailFerme;
use App\Entity\TypeBetail;
use App\Repository\BetailRepository;
use App\Repository\FermeRepository;
use App\Repository\BatimentRepository;
use App\Repository\ZoneRepository;
use App\Repository\FamilleBetailRepository;
use App\Repository\FamilleBetailFermeRepository;
use App\Repository\TypeBetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/betail')]
class BetailController extends AbstractController
{
    #[Route('/ferme/{fermeId}', name: 'betail_index', requirements: ['fermeId' => '\d+'])]
    public function index(
        int $fermeId,
        FamilleBetailFermeRepository $familleBetailFermeRepository, 
        FamilleBetailRepository $familleBetailRepository,
        FermeRepository $fermeRepository,
        Request $request
    ): Response {
        $fermeActive = $fermeRepository->find($fermeId);
        
        if (!$fermeActive) {
            throw $this->createNotFoundException('Ferme non trouvée');
        }
        
        // Récupérer les statistiques des familles associées à cette ferme
        $statistiquesFamillesExistantes = $familleBetailFermeRepository->getStatistiquesParFerme($fermeActive);
        
        // Transformer en format attendu par le template
        $statistiquesFamilles = [];
        foreach ($statistiquesFamillesExistantes as $stats) {
            $statistiquesFamilles[$stats['nomFamille']] = [
                'nom' => $stats['nomFamille'],
                'effectif' => (int)$stats['effectifTotal'] ?? 0,
                'description' => $stats['descriptifPersonnalise'] ?: $stats['descriptionFamille'],
                'sousTypes' => [], // Sera rempli si nécessaire
            ];
        }
        
        // Familles disponibles mais non encore associées (pour le formulaire d'ajout)
        $famillesDisponibles = $familleBetailRepository->findNonAssocieesAFerme($fermeActive);
        
        return $this->render('betail/families_overview.html.twig', [
            'statistiquesFamilles' => $statistiquesFamilles,
            'fermeActive' => $fermeActive,
            'famillesDisponibles' => $famillesDisponibles,
        ]);
    }

    #[Route('/ferme/{fermeId}/famille/{nomFamille}', name: 'betail_famille', requirements: ['fermeId' => '\d+'])]
    public function famille(
        int $fermeId,
        string $nomFamille,
        FamilleBetailRepository $familleBetailRepository,
        FamilleBetailFermeRepository $familleBetailFermeRepository,
        TypeBetailRepository $typeBetailRepository,
        FermeRepository $fermeRepository,
        Request $request
    ): Response {
        $fermeActive = $fermeRepository->find($fermeId);
        
        if (!$fermeActive) {
            throw $this->createNotFoundException('Ferme non trouvée');
        }
        
        // Trouve la famille de bétail globale
        $familleBetail = $familleBetailRepository->findByNom($nomFamille);
        
        if (!$familleBetail) {
            throw $this->createNotFoundException('Famille de bétail non trouvée');
        }
        
        // Trouve l'association famille-ferme
        $familleBetailFerme = $familleBetailFermeRepository->findByFamilleAndFerme($familleBetail, $fermeActive);
        
        if (!$familleBetailFerme) {
            throw $this->createNotFoundException('Cette famille n\'est pas associée à cette ferme');
        }
        
        // Récupérer les statistiques des types pour cette famille-ferme
        $statistiquesSousTypes = $typeBetailRepository->getStatistiquesParFamilleFerme($familleBetailFerme);
        
        // Transformer en format attendu par le template
        $statistiquesSousTypesFormatees = [];
        foreach ($statistiquesSousTypes as $stats) {
            $statistiquesSousTypesFormatees[$stats['nom']] = [
                'nom' => $stats['nom'],
                'effectif' => (int)$stats['effectif'],
            ];
        }
        
        return $this->render('betail/family_detail.html.twig', [
            'type' => $nomFamille,
            'typeName' => $familleBetail->getNom(),
            'statistiquesSousTypes' => $statistiquesSousTypesFormatees,
            'fermeActive' => $fermeActive,
            'description' => $familleBetailFerme->getDescriptionEffective(),
        ]);
    }

    #[Route('/ferme/{fermeId}/famille/{nomFamille}/type/{nomType}', name: 'betail_type', requirements: ['fermeId' => '\d+'])]
    public function type(
        int $fermeId,
        string $nomFamille,
        string $nomType,
        FamilleBetailRepository $familleBetailRepository,
        FamilleBetailFermeRepository $familleBetailFermeRepository,
        TypeBetailRepository $typeBetailRepository,
        FermeRepository $fermeRepository,
        Request $request
    ): Response {
        $fermeActive = $fermeRepository->find($fermeId);
        
        if (!$fermeActive) {
            throw $this->createNotFoundException('Ferme non trouvée');
        }
        
        // Trouve la famille de bétail globale
        $familleBetail = $familleBetailRepository->findByNom($nomFamille);
        
        if (!$familleBetail) {
            throw $this->createNotFoundException('Famille de bétail non trouvée');
        }
        
        // Trouve l'association famille-ferme
        $familleBetailFerme = $familleBetailFermeRepository->findByFamilleAndFerme($familleBetail, $fermeActive);
        
        if (!$familleBetailFerme) {
            throw $this->createNotFoundException('Cette famille n\'est pas associée à cette ferme');
        }
        
        // Trouve le type de bétail pour cette famille-ferme
        $typeBetail = $typeBetailRepository->findByNomAndFamilleFerme($nomType, $familleBetailFerme);
        
        if (!$typeBetail) {
            throw $this->createNotFoundException('Type de bétail non trouvé');
        }
        
        // Récupérer tous les animaux de ce type
        $betails = $typeBetail->getAnimaux()->toArray();
        
        return $this->render('betail/type_management.html.twig', [
            'betails' => $betails,
            'type' => $nomFamille,
            'sousType' => $nomType,
            'typeName' => $familleBetail->getNom(),
            'sousTypeName' => $typeBetail->getNom(),
            'fermeActive' => $fermeActive,
        ]);
    }

    #[Route('/ferme/{fermeId}/famille/associate', name: 'betail_famille_associate', requirements: ['fermeId' => '\d+'], methods: ['POST'])]
    public function associateFamille(
        int $fermeId,
        FamilleBetailRepository $familleBetailRepository,
        FamilleBetailFermeRepository $familleBetailFermeRepository,
        FermeRepository $fermeRepository,
        Request $request
    ): Response {
        try {
            $fermeActive = $fermeRepository->find($fermeId);
            
            if (!$fermeActive) {
                return $this->json(['success' => false, 'message' => 'Ferme non trouvée'], 404);
            }

            $data = json_decode($request->getContent(), true);
            $familleId = $data['familleId'] ?? null;
            $descriptifPersonnalise = $data['descriptifPersonnalise'] ?? '';

            // Validation
            if (empty($familleId)) {
                return $this->json(['success' => false, 'message' => 'ID de famille requis'], 400);
            }

            // Trouve la famille de bétail globale
            $familleBetail = $familleBetailRepository->find($familleId);
            
            if (!$familleBetail) {
                return $this->json(['success' => false, 'message' => 'Famille de bétail non trouvée'], 400);
            }

            // Vérifier si l'association existe déjà
            if ($familleBetailFermeRepository->existePourFerme($familleBetail, $fermeActive)) {
                return $this->json(['success' => false, 'message' => 'Cette famille est déjà associée à cette ferme'], 400);
            }

            // Créer l'association famille-ferme
            $familleBetailFerme = new FamilleBetailFerme();
            $familleBetailFerme->setFerme($fermeActive)
                              ->setFamille($familleBetail)
                              ->setDescriptifPersonnalise($descriptifPersonnalise);

            $familleBetailFermeRepository->save($familleBetailFerme, true);

            return $this->json(['success' => true, 'message' => 'Famille associée avec succès']);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false, 
                'message' => 'Erreur lors de l\'association: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    #[Route('/famille/create', name: 'betail_famille_create_global', methods: ['POST'])]
    public function createFamilleGlobale(
        FamilleBetailRepository $familleBetailRepository,
        Request $request
    ): Response {
        $data = json_decode($request->getContent(), true);
        $nom = trim($data['nom'] ?? '');
        $description = trim($data['description'] ?? '');

        // Validation
        if (empty($nom)) {
            return $this->json(['success' => false, 'message' => 'Le nom de la famille est requis'], 400);
        }

        // Vérifier si une famille avec ce nom existe déjà
        if ($familleBetailRepository->findByNom($nom)) {
            return $this->json(['success' => false, 'message' => 'Une famille avec ce nom existe déjà'], 400);
        }

        // Créer la nouvelle famille globale
        $familleBetail = new FamilleBetail();
        $familleBetail->setNom($nom)
                     ->setDescription($description);

        $familleBetailRepository->save($familleBetail, true);

        return $this->json([
            'success' => true, 
            'message' => 'Nouvelle famille créée avec succès',
            'famille' => [
                'id' => $familleBetail->getId(),
                'nom' => $familleBetail->getNom(),
                'description' => $familleBetail->getDescription()
            ]
        ]);
    }

    #[Route('/zone/{id}', name: 'betail_zone')]
    public function betailParZone(
        Zone $zone, 
        BetailRepository $betailRepository
    ): Response {
        $betails = $betailRepository->findByZone($zone);
        
        return $this->render('betail/zone.html.twig', [
            'zone' => $zone,
            'betails' => $betails,
            'batiment' => $zone->getBatiment(),
            'ferme' => $zone->getFerme(),
        ]);
    }

    #[Route('/details/{id}', name: 'betail_show')]
    public function show(Betail $betail): Response
    {
        return $this->render('betail/show.html.twig', [
            'betail' => $betail,
        ]);
    }
}