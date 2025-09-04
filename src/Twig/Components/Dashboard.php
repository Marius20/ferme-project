<?php

namespace App\Twig\Components;

use App\Repository\FermeRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
class Dashboard
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $selectedFermeId = 1;

    public function __construct(
        private FermeRepository $fermeRepository
    ) {
    }

    public function getStatistiques(): array
    {
        return $this->fermeRepository->getStatistiquesGlobales($this->selectedFermeId);
    }

    public function getAlertes(): array
    {
        return $this->fermeRepository->getAlertes($this->selectedFermeId);
    }

    public function getFermes(): array
    {
        return $this->fermeRepository->findAll();
    }

    public function getEvolutionEffectifs(): array
    {
        return $this->fermeRepository->getEvolutionEffectifs($this->selectedFermeId);
    }

    public function getRentabiliteActivite(): array
    {
        $dateDebut = new \DateTime('first day of this month');
        $dateFin = new \DateTime('last day of this month');
        
        return $this->fermeRepository->getRentabiliteParActivite($this->selectedFermeId, $dateDebut, $dateFin);
    }
}