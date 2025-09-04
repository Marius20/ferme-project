<?php

namespace App\Controller;

use App\Entity\Batiment;
use App\Entity\Ferme;
use App\Entity\ZoneFerme;
use App\Repository\BatimentRepository;
use App\Repository\FermeRepository;
use App\Repository\ZoneRepository;
use App\Repository\ZoneFermeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/batiments')]
class BatimentController extends AbstractController
{
    #[Route('', name: 'batiment_index')]
    public function index(
        BatimentRepository $batimentRepository, 
        FermeRepository $fermeRepository,
        Request $request
    ): Response {
        $fermes = $fermeRepository->findAll();
        
        // Filtres
        $criteria = [];
        if ($fermeId = $request->query->get('ferme')) {
            $criteria['ferme'] = $fermeId;
        }
        if ($type = $request->query->get('type')) {
            $criteria['type'] = $type;
        }
        if ($statut = $request->query->get('statut')) {
            $criteria['statut'] = $statut;
        }
        if ($search = $request->query->get('search')) {
            $criteria['search'] = $search;
        }
        
        $batiments = $batimentRepository->findByCriteria($criteria);
        $occupationStats = $batimentRepository->getOccupationStats();
        
        return $this->render('batiment/index.html.twig', [
            'batiments' => $batiments,
            'fermes' => $fermes,
            'occupationStats' => $occupationStats,
            'filters' => $request->query->all(),
            'types' => Batiment::getTypesDisponibles(),
            'statuts' => Batiment::getStatutsDisponibles(),
        ]);
    }

    #[Route('/ferme/{id}', name: 'batiment_ferme')]
    public function batimentParFerme(
        Ferme $ferme, 
        BatimentRepository $batimentRepository
    ): Response {
        $batiments = $batimentRepository->findByFermeWithZones($ferme);
        $occupationStats = $batimentRepository->getOccupationStats($ferme);
        $alertes = $batimentRepository->findWithCapacityAlerts($ferme);
        
        return $this->render('batiment/ferme.html.twig', [
            'ferme' => $ferme,
            'batiments' => $batiments,
            'occupationStats' => $occupationStats,
            'alertes' => $alertes,
        ]);
    }

    #[Route('/{id}', name: 'batiment_show')]
    public function show(
        Batiment $batiment,
        ZoneRepository $zoneRepository
    ): Response {
        $zones = $zoneRepository->findByBatiment($batiment);
        $zonesDisponibles = $zoneRepository->findAvailableByBatiment($batiment);
        $occupationStats = $zoneRepository->getOccupationStats();
        
        return $this->render('batiment/show.html.twig', [
            'batiment' => $batiment,
            'zones' => $zones,
            'zonesDisponibles' => $zonesDisponibles,
            'occupationStats' => $occupationStats,
        ]);
    }

    #[Route('/{id}/zones', name: 'batiment_zones')]
    public function zones(
        Batiment $batiment,
        ZoneRepository $zoneRepository
    ): Response {
        $zones = $zoneRepository->findByBatiment($batiment);
        $occupationDetails = $zoneRepository->getOccupationStats();
        
        return $this->render('batiment/zones.html.twig', [
            'batiment' => $batiment,
            'zones' => $zones,
            'occupationDetails' => $occupationDetails,
        ]);
    }

    #[Route('/create', name: 'batiment_create', methods: ['POST'])]
    public function create(
        Request $request,
        BatimentRepository $batimentRepository,
        FermeRepository $fermeRepository,
        ZoneFermeRepository $zoneFermeRepository
    ): Response {
        try {
            $data = json_decode($request->getContent(), true);
            
            // Validation des données
            if (empty($data['nom'])) {
                return $this->json(['success' => false, 'message' => 'Le nom est requis'], 400);
            }
            
            if (empty($data['fermeId'])) {
                return $this->json(['success' => false, 'message' => 'ID de ferme requis'], 400);
            }

            // Récupération de la ferme
            $ferme = $fermeRepository->find($data['fermeId']);
            if (!$ferme) {
                return $this->json(['success' => false, 'message' => 'Ferme non trouvée'], 404);
            }

            // Récupération de la zone ferme (optionnelle)
            $zoneFerme = null;
            if (!empty($data['zoneFermeId'])) {
                $zoneFerme = $zoneFermeRepository->find($data['zoneFermeId']);
                if (!$zoneFerme) {
                    return $this->json(['success' => false, 'message' => 'Zone ferme non trouvée'], 404);
                }
            }

            // Génération du numéro d'identification
            $numeroIdentification = $data['numeroIdentification'] ?? null;
            if (empty($numeroIdentification)) {
                $numeroIdentification = 'BAT-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $data['nom']), 0, 3)) . '-' . time();
            }

            // Vérifier l'unicité du numéro d'identification
            if ($batimentRepository->findOneBy(['numeroIdentification' => $numeroIdentification])) {
                $numeroIdentification .= '-' . rand(100, 999);
            }

            // Création du bâtiment
            $batiment = new Batiment();
            $batiment->setNom($data['nom'])
                    ->setNumeroIdentification($numeroIdentification)
                    ->setType($data['type'] ?? Batiment::TYPE_HANGAR)
                    ->setDescription($data['description'] ?? '')
                    ->setSuperficie($data['superficie'] ?? null)
                    ->setCapaciteMaximale($data['capaciteMaximale'] ?? null)
                    ->setStatut($data['statut'] ?? Batiment::STATUT_ACTIF)
                    ->setDateConstruction($data['dateConstruction'] ? new \DateTime($data['dateConstruction']) : null)
                    ->setEquipements($data['equipements'] ?? '')
                    ->setNotes($data['notes'] ?? '')
                    ->setFerme($ferme)
                    ->setZoneFerme($zoneFerme);

            $batimentRepository->save($batiment, true);

            return $this->json([
                'success' => true,
                'message' => 'Bâtiment créé avec succès',
                'batiment' => [
                    'id' => $batiment->getId(),
                    'nom' => $batiment->getNom(),
                    'numeroIdentification' => $batiment->getNumeroIdentification(),
                    'type' => $batiment->getType()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
    }
}