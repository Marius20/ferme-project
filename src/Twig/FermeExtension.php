<?php

namespace App\Twig;

use App\Repository\FermeRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class FermeExtension extends AbstractExtension implements GlobalsInterface
{
    public function __construct(
        private FermeRepository $fermeRepository,
        private RequestStack $requestStack
    ) {}

    public function getGlobals(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $request ? $request->getSession() : null;
        
        $fermeActive = null;
        $fermeActiveId = $session ? $session->get('ferme_active_id') : null;
        
        if ($fermeActiveId) {
            $fermeActive = $this->fermeRepository->find($fermeActiveId);
        }
        
        $fermesDisponibles = $this->fermeRepository->findAll();
        
        return [
            'ferme_active' => $fermeActive,
            'fermes_disponibles' => $fermesDisponibles,
        ];
    }
}