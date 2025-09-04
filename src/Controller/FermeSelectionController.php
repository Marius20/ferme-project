<?php

namespace App\Controller;

use App\Repository\FermeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FermeSelectionController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(FermeRepository $fermeRepository, SessionInterface $session): Response
    {
        // Si une ferme est déjà sélectionnée, rediriger vers le dashboard de cette ferme
        if ($fermeId = $session->get('ferme_active_id')) {
            return $this->redirectToRoute('dashboard_ferme', ['id' => $fermeId]);
        }

        // Sinon, afficher la page de sélection des fermes
        $fermes = $fermeRepository->findAll();
        
        return $this->render('ferme/selection.html.twig', [
            'fermes' => $fermes,
        ]);
    }

    #[Route('/ferme/select/{id}', name: 'ferme_select')]
    public function selectFerme(int $id, FermeRepository $fermeRepository, SessionInterface $session): Response
    {
        $ferme = $fermeRepository->find($id);
        
        if (!$ferme) {
            $this->addFlash('error', 'Ferme introuvable.');
            return $this->redirectToRoute('app_home');
        }

        // Stocker la ferme active en session
        $session->set('ferme_active_id', $ferme->getId());
        $session->set('ferme_active_nom', $ferme->getNom());
        
        $this->addFlash('success', sprintf('Ferme "%s" sélectionnée.', $ferme->getNom()));
        
        return $this->redirectToRoute('dashboard_ferme', ['id' => $ferme->getId()]);
    }

    #[Route('/ferme/switch', name: 'ferme_switch')]
    public function switchFerme(FermeRepository $fermeRepository, SessionInterface $session): Response
    {
        $fermes = $fermeRepository->findAll();
        $fermeActive = null;
        
        if ($fermeActiveId = $session->get('ferme_active_id')) {
            $fermeActive = $fermeRepository->find($fermeActiveId);
        }
        
        return $this->render('ferme/switch.html.twig', [
            'fermes' => $fermes,
            'ferme_active' => $fermeActive,
        ]);
    }

    #[Route('/ferme/clear', name: 'ferme_clear_selection')]
    public function clearSelection(SessionInterface $session): Response
    {
        $session->remove('ferme_active_id');
        $session->remove('ferme_active_nom');
        
        $this->addFlash('info', 'Sélection de ferme annulée.');
        
        return $this->redirectToRoute('app_home');
    }
}