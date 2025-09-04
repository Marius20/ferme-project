<?php

namespace App\Controller;

use App\Repository\StockRepository;
use App\Repository\FermeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockController extends AbstractController
{
    #[Route('/stocks', name: 'stocks')]
    public function index(StockRepository $stockRepository, FermeRepository $fermeRepository): Response
    {
        $fermes = $fermeRepository->findAll();
        $stocks = $stockRepository->findAll();
        $alertes = $stockRepository->getStocksCritiques();
        
        return $this->render('stocks/index.html.twig', [
            'fermes' => $fermes,
            'stocks' => $stocks,
            'alertes' => $alertes,
        ]);
    }

    #[Route('/stocks/ferme/{id}', name: 'stocks_ferme')]
    public function stocksParFerme(int $id, StockRepository $stockRepository): Response
    {
        $stocks = $stockRepository->findBy(['ferme' => $id]);
        
        return $this->render('stocks/ferme.html.twig', [
            'stocks' => $stocks,
            'fermeId' => $id,
        ]);
    }
}