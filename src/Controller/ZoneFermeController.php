<?php

namespace App\Controller;

use App\Entity\ZoneFerme;
use App\Entity\Ferme;
use App\Repository\ZoneFermeRepository;
use App\Repository\FermeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/zone-ferme')]
class ZoneFermeController extends AbstractController
{
    #[Route('/create', name: 'zone_ferme_create', methods: ['POST'])]
    public function create(
        Request $request,
        ZoneFermeRepository $zoneFermeRepository,
        FermeRepository $fermeRepository
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

            // Génération du numéro d'identification
            $numeroIdentification = $data['numeroIdentification'] ?? null;
            if (empty($numeroIdentification)) {
                // Générer un numéro unique basé sur le nom et un timestamp
                $numeroIdentification = 'ZF-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $data['nom']), 0, 3)) . '-' . time();
            }

            // Vérifier l'unicité du numéro d'identification
            if ($zoneFermeRepository->findOneBy(['numeroIdentification' => $numeroIdentification])) {
                $numeroIdentification .= '-' . rand(100, 999);
            }

            // Création de la zone ferme
            $zoneFerme = new ZoneFerme();
            $zoneFerme->setNom($data['nom'])
                     ->setNumeroIdentification($numeroIdentification)
                     ->setType($data['type'] ?? ZoneFerme::TYPE_PRODUCTION)
                     ->setDescription($data['description'] ?? '')
                     ->setSuperficie($data['superficie'] ?? null)
                     ->setLocalisation($data['localisation'] ?? '')
                     ->setCaracteristiques($data['caracteristiques'] ?? '')
                     ->setAcces($data['acces'] ?? '')
                     ->setNotes($data['notes'] ?? '')
                     ->setFerme($ferme);

            $zoneFermeRepository->save($zoneFerme, true);

            return $this->json([
                'success' => true,
                'message' => 'Zone ferme créée avec succès',
                'zoneFerme' => [
                    'id' => $zoneFerme->getId(),
                    'nom' => $zoneFerme->getNom(),
                    'numeroIdentification' => $zoneFerme->getNumeroIdentification(),
                    'type' => $zoneFerme->getType()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/update/{id}', name: 'zone_ferme_update', methods: ['PUT', 'GET'])]
    public function update(
        int $id,
        Request $request,
        ZoneFermeRepository $zoneFermeRepository
    ): Response {
        try {
            $zoneFerme = $zoneFermeRepository->find($id);
            if (!$zoneFerme) {
                return $this->json(['success' => false, 'message' => 'Zone ferme non trouvée'], 404);
            }

            // Si c'est une requête GET, renvoyer les données de la zone
            if ($request->getMethod() === 'GET') {
                return $this->json([
                    'success' => true,
                    'zoneFerme' => [
                        'id' => $zoneFerme->getId(),
                        'nom' => $zoneFerme->getNom(),
                        'numeroIdentification' => $zoneFerme->getNumeroIdentification(),
                        'type' => $zoneFerme->getType(),
                        'description' => $zoneFerme->getDescription(),
                        'superficie' => $zoneFerme->getSuperficie(),
                        'localisation' => $zoneFerme->getLocalisation(),
                        'caracteristiques' => $zoneFerme->getCaracteristiques(),
                        'acces' => $zoneFerme->getAcces(),
                        'notes' => $zoneFerme->getNotes(),
                        'actif' => $zoneFerme->isActif()
                    ]
                ]);
            }

            $data = json_decode($request->getContent(), true);
            
            // Validation des données
            if (empty($data['nom'])) {
                return $this->json(['success' => false, 'message' => 'Le nom est requis'], 400);
            }

            // Mise à jour des données
            $zoneFerme->setNom($data['nom'])
                     ->setType($data['type'] ?? $zoneFerme->getType())
                     ->setDescription($data['description'] ?? '')
                     ->setSuperficie($data['superficie'] ?? null)
                     ->setLocalisation($data['localisation'] ?? '')
                     ->setCaracteristiques($data['caracteristiques'] ?? '')
                     ->setAcces($data['acces'] ?? '')
                     ->setNotes($data['notes'] ?? '');

            $zoneFermeRepository->save($zoneFerme, true);

            return $this->json([
                'success' => true,
                'message' => 'Zone ferme modifiée avec succès',
                'zoneFerme' => [
                    'id' => $zoneFerme->getId(),
                    'nom' => $zoneFerme->getNom(),
                    'type' => $zoneFerme->getType(),
                    'description' => $zoneFerme->getDescription(),
                    'superficie' => $zoneFerme->getSuperficie(),
                    'localisation' => $zoneFerme->getLocalisation()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors de la modification: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/toggle-status/{id}', name: 'zone_ferme_toggle_status', methods: ['PATCH'])]
    public function toggleStatus(
        int $id,
        ZoneFermeRepository $zoneFermeRepository
    ): Response {
        try {
            $zoneFerme = $zoneFermeRepository->find($id);
            if (!$zoneFerme) {
                return $this->json(['success' => false, 'message' => 'Zone ferme non trouvée'], 404);
            }

            // Toggle du statut actif
            $nouveauStatut = !$zoneFerme->isActif();
            $zoneFerme->setActif($nouveauStatut);

            $zoneFermeRepository->save($zoneFerme, true);

            $message = $nouveauStatut ? 'Zone ferme activée avec succès' : 'Zone ferme désactivée avec succès';

            return $this->json([
                'success' => true,
                'message' => $message,
                'zoneFerme' => [
                    'id' => $zoneFerme->getId(),
                    'nom' => $zoneFerme->getNom(),
                    'actif' => $zoneFerme->isActif()
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur lors du changement de statut: ' . $e->getMessage()
            ], 500);
        }
    }
}