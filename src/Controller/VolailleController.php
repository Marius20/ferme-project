<?php

namespace App\Controller;

use App\Repository\VolailleRepository;
use App\Repository\FermeRepository;
use App\Repository\ProductionOeufRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VolailleController extends AbstractController
{
    #[Route('/volailles', name: 'volailles')]
    public function index(VolailleRepository $volailleRepository, FermeRepository $fermeRepository, ProductionOeufRepository $productionRepository): Response
    {
        $fermes = $fermeRepository->findAll();
        $volailles = $volailleRepository->findAll();
        
        // Statistiques de production d'œufs
        $productionStats = $productionRepository->getProductionStatistics();
        
        return $this->render('volailles/index.html.twig', [
            'fermes' => $fermes,
            'volailles' => $volailles,
            'productionStats' => $productionStats,
        ]);
    }

    #[Route('/volailles/ferme/{id}', name: 'volailles_ferme')]
    public function volaillesParFerme(int $id, VolailleRepository $volailleRepository): Response
    {
        $volailles = $volailleRepository->findBy(['ferme' => $id]);
        
        return $this->render('volailles/ferme.html.twig', [
            'volailles' => $volailles,
            'fermeId' => $id,
        ]);
    }

    #[Route('/volailles/{id}/production', name: 'volaille_production')]
    public function production(int $id, VolailleRepository $volailleRepository, ProductionOeufRepository $productionRepository): Response
    {
        $volaille = $volailleRepository->find($id);
        
        if (!$volaille) {
            throw $this->createNotFoundException('Volaille non trouvée');
        }
        
        $productions = $productionRepository->findBy(
            ['volaille' => $volaille], 
            ['date' => 'DESC'], 
            30 // Derniers 30 jours
        );
        
        return $this->render('volailles/production.html.twig', [
            'volaille' => $volaille,
            'productions' => $productions,
        ]);
    }
}