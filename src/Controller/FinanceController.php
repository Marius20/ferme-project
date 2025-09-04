<?php

namespace App\Controller;

use App\Repository\TransactionRepository;
use App\Repository\FermeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinanceController extends AbstractController
{
    #[Route('/finances', name: 'finances')]
    public function index(TransactionRepository $transactionRepository, FermeRepository $fermeRepository): Response
    {
        $fermes = $fermeRepository->findAll();
        $transactions = $transactionRepository->findBy([], ['dateTransaction' => 'DESC'], 50);
        
        // Statistiques du mois actuel
        $debut = new \DateTime('first day of this month');
        $fin = new \DateTime('last day of this month');
        
        $recettesMois = $transactionRepository->getTotalByTypeAndPeriod('recette', $debut, $fin);
        $depensesMois = $transactionRepository->getTotalByTypeAndPeriod('depense', $debut, $fin);
        $beneficeMois = $recettesMois - $depensesMois;
        
        // Évolution des 6 derniers mois
        $evolutionMois = $transactionRepository->getEvolutionFinanciere(6);
        
        return $this->render('finances/index.html.twig', [
            'fermes' => $fermes,
            'transactions' => $transactions,
            'recettesMois' => $recettesMois,
            'depensesMois' => $depensesMois,
            'beneficeMois' => $beneficeMois,
            'evolutionMois' => $evolutionMois,
        ]);
    }

    #[Route('/finances/ferme/{id}', name: 'finances_ferme')]
    public function financesParFerme(int $id, TransactionRepository $transactionRepository): Response
    {
        $transactions = $transactionRepository->findBy(
            ['ferme' => $id], 
            ['dateTransaction' => 'DESC'], 
            100
        );
        
        return $this->render('finances/ferme.html.twig', [
            'transactions' => $transactions,
            'fermeId' => $id,
        ]);
    }

    #[Route('/finances/rapport', name: 'finances_rapport')]
    public function rapport(TransactionRepository $transactionRepository): Response
    {
        // Statistiques par catégorie pour les 12 derniers mois
        $debut = new \DateTime('-12 months');
        $fin = new \DateTime();
        
        $recettesParCategorie = $transactionRepository->getRecettesParCategorie($debut, $fin);
        $depensesParCategorie = $transactionRepository->getDepensesParCategorie($debut, $fin);
        
        return $this->render('finances/rapport.html.twig', [
            'recettesParCategorie' => $recettesParCategorie,
            'depensesParCategorie' => $depensesParCategorie,
            'periodeDebut' => $debut,
            'periodeFin' => $fin,
        ]);
    }
}