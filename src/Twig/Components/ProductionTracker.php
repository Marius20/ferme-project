<?php

namespace App\Twig\Components;

use App\Repository\VolailleRepository;
use App\Repository\ProductionOeufRepository;
use App\Repository\FermeRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
class ProductionTracker
{
    use DefaultActionTrait;

    #[LiveProp]
    public int $selectedFermeId = 1;

    #[LiveProp]
    public string $periode = 'semaine'; // semaine, mois, trimestre

    public function __construct(
        private VolailleRepository $volailleRepository,
        private ProductionOeufRepository $productionRepository,
        private FermeRepository $fermeRepository
    ) {
    }

    public function getProductionData(): array
    {
        $dateDebut = match($this->periode) {
            'semaine' => new \DateTime('-1 week'),
            'mois' => new \DateTime('-1 month'),
            'trimestre' => new \DateTime('-3 months'),
            default => new \DateTime('-1 week')
        };

        return $this->productionRepository->getProductionByPeriod(
            $this->selectedFermeId,
            $dateDebut,
            new \DateTime()
        );
    }

    public function getVolaillesPondeuses(): array
    {
        return $this->volailleRepository->findPondeusesByFerme($this->selectedFermeId);
    }

    public function getStatistiquesProduction(): array
    {
        $data = $this->getProductionData();
        
        $total = array_sum(array_column($data, 'quantite'));
        $moyenne = count($data) > 0 ? round($total / count($data), 1) : 0;
        
        // Calcul de la tendance (7 derniers jours vs 7 précédents)
        $recent = array_slice($data, -7);
        $precedent = array_slice($data, -14, 7);
        
        $totalRecent = array_sum(array_column($recent, 'quantite'));
        $totalPrecedent = array_sum(array_column($precedent, 'quantite'));
        
        $tendance = $totalPrecedent > 0 
            ? round((($totalRecent - $totalPrecedent) / $totalPrecedent) * 100, 1)
            : 0;

        return [
            'total' => $total,
            'moyenne' => $moyenne,
            'tendance' => $tendance,
            'jours_production' => count($data)
        ];
    }

    public function getFermes(): array
    {
        return $this->fermeRepository->findAll();
    }

    public function getProductionObjectif(): array
    {
        $volailles = $this->getVolaillesPondeuses();
        $totalPoules = array_sum(array_map(fn($v) => $v->getNombreTete(), $volailles));
        
        // Objectif moyen: 80% des poules pondent chaque jour
        $objectifJour = round($totalPoules * 0.8);
        
        return [
            'objectif_jour' => $objectifJour,
            'total_poules' => $totalPoules
        ];
    }
}