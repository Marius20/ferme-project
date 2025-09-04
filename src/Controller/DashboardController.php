<?php

namespace App\Controller;

use App\Entity\Ferme;
use App\Repository\FermeRepository;
use App\Repository\BetailRepository;
use App\Repository\BatimentRepository;
use App\Repository\VolailleRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard/ferme/{id}', name: 'dashboard_ferme')]
    public function dashboardFerme(
        Ferme $ferme, 
        BetailRepository $betailRepository,
        BatimentRepository $batimentRepository,
        VolailleRepository $volailleRepository,
        TransactionRepository $transactionRepository,
        SessionInterface $session
    ): Response {
        // Mettre à jour la ferme active en session
        $session->set('ferme_active_id', $ferme->getId());
        $session->set('ferme_active_nom', $ferme->getNom());

        // Statistiques générales de la ferme
        $statistiques = [
            'betail' => [
                'total' => $betailRepository->countActiveByFerme($ferme),
                'par_type' => $betailRepository->countActiveByFamilleAndFerme($ferme),
            ],
            'volailles' => [
                'total_lots' => $volailleRepository->countByFerme($ferme),
                'total_effectif' => $volailleRepository->getTotalEffectifByFerme($ferme),
            ],
            'batiments' => [
                'total' => $batimentRepository->countByFerme($ferme),
                'par_type' => $batimentRepository->countByTypeAndFerme($ferme),
                'occupation' => $batimentRepository->getOccupationStats($ferme),
            ],
            'finances' => [
                'recettes_mois' => $transactionRepository->getTotalRecettesMoisByFerme($ferme),
                'depenses_mois' => $transactionRepository->getTotalDepensesMoisByFerme($ferme),
                'benefice_mois' => 0, // Sera calculé
            ],
        ];

        // Calculer le bénéfice
        $statistiques['finances']['benefice_mois'] = 
            ($statistiques['finances']['recettes_mois'] ?? 0) - 
            ($statistiques['finances']['depenses_mois'] ?? 0);

        // Transactions récentes
        $transactionsRecentes = $transactionRepository->findRecentByFerme($ferme, 5);

        return $this->render('dashboard/ferme.html.twig', [
            'ferme' => $ferme,
            'statistiques' => $statistiques,
            'transactions_recentes' => $transactionsRecentes,
        ]);
    }

    #[Route('/dashboard/general', name: 'dashboard_general')]
    public function dashboardGeneral(
        FermeRepository $fermeRepository,
        BetailRepository $betailRepository,
        SessionInterface $session
    ): Response {
        // Dashboard global (toutes fermes)
        $fermes = $fermeRepository->findAll();
        $statistiquesGlobales = [
            'fermes_total' => count($fermes),
            'betail_total' => $betailRepository->countAllActive(),
        ];

        return $this->render('dashboard/general.html.twig', [
            'fermes' => $fermes,
            'statistiques' => $statistiquesGlobales,
        ]);
    }
}