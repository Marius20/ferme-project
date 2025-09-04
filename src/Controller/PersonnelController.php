<?php

namespace App\Controller;

use App\Repository\EmployeRepository;
use App\Repository\FermeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonnelController extends AbstractController
{
    #[Route('/personnel', name: 'personnel')]
    public function index(EmployeRepository $employeRepository, FermeRepository $fermeRepository): Response
    {
        $fermes = $fermeRepository->findAll();
        $employes = $employeRepository->findAll();
        
        // Statistiques
        $totalEmployes = count($employes);
        $masseSalariale = array_sum(array_map(fn($e) => $e->getSalaire(), $employes));
        
        // Répartition par poste
        $repartitionPostes = [];
        foreach ($employes as $employe) {
            $poste = $employe->getPoste();
            $repartitionPostes[$poste] = ($repartitionPostes[$poste] ?? 0) + 1;
        }
        
        return $this->render('personnel/index.html.twig', [
            'fermes' => $fermes,
            'employes' => $employes,
            'totalEmployes' => $totalEmployes,
            'masseSalariale' => $masseSalariale,
            'repartitionPostes' => $repartitionPostes,
        ]);
    }

    #[Route('/personnel/ferme/{id}', name: 'personnel_ferme')]
    public function personnelParFerme(int $id, EmployeRepository $employeRepository, FermeRepository $fermeRepository): Response
    {
        $ferme = $fermeRepository->find($id);
        $employes = $employeRepository->findBy(['ferme' => $id]);
        
        return $this->render('personnel/ferme.html.twig', [
            'ferme' => $ferme,
            'employes' => $employes,
        ]);
    }

    #[Route('/personnel/{id}', name: 'personnel_details')]
    public function details(int $id, EmployeRepository $employeRepository): Response
    {
        $employe = $employeRepository->find($id);
        
        if (!$employe) {
            throw $this->createNotFoundException('Employé non trouvé');
        }
        
        // Calcul ancienneté
        $anciennete = $employe->getDateEmbauche()->diff(new \DateTime());
        
        return $this->render('personnel/details.html.twig', [
            'employe' => $employe,
            'anciennete' => $anciennete,
        ]);
    }
}