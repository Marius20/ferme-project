<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Entity\Batiment;
use App\Repository\ZoneRepository;
use App\Repository\BatimentRepository;
use App\Repository\BetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/zones')]
class ZoneController extends AbstractController
{
    #[Route('', name: 'zone_index')]
    public function index(
        ZoneRepository $zoneRepository,
        BatimentRepository $batimentRepository,
        Request $request
    ): Response {
        $batiments = $batimentRepository->findAll();
        
        // Filtres
        $criteria = [];
        if ($batimentId = $request->query->get('batiment')) {
            $criteria['batiment'] = $batimentId;
        }
        if ($type = $request->query->get('type')) {
            $criteria['type'] = $type;
        }
        if ($statut = $request->query->get('statut')) {
            $criteria['statut'] = $statut;
        }
        if ($request->query->get('disponible')) {
            $criteria['disponible'] = true;
        }
        if ($search = $request->query->get('search')) {
            $criteria['search'] = $search;
        }
        
        $zones = $zoneRepository->findByCriteria($criteria);
        $occupationStats = $zoneRepository->getOccupationStats();
        $alertes = $zoneRepository->findWithCapacityAlerts();
        
        return $this->render('zone/index.html.twig', [
            'zones' => $zones,
            'batiments' => $batiments,
            'occupationStats' => $occupationStats,
            'alertes' => $alertes,
            'filters' => $request->query->all(),
            'types' => Zone::getTypesDisponibles(),
            'statuts' => Zone::getStatutsDisponibles(),
        ]);
    }

    #[Route('/batiment/{id}', name: 'zone_batiment')]
    public function zoneParBatiment(
        Batiment $batiment,
        ZoneRepository $zoneRepository
    ): Response {
        $zones = $zoneRepository->findByBatiment($batiment);
        $zonesDisponibles = $zoneRepository->findAvailableByBatiment($batiment);
        $occupationStats = $zoneRepository->getOccupationStats();
        
        return $this->render('zone/batiment.html.twig', [
            'batiment' => $batiment,
            'zones' => $zones,
            'zonesDisponibles' => $zonesDisponibles,
            'occupationStats' => $occupationStats,
        ]);
    }

    #[Route('/{id}', name: 'zone_show')]
    public function show(
        Zone $zone,
        BetailRepository $betailRepository
    ): Response {
        $betails = $betailRepository->findByZone($zone);
        
        return $this->render('zone/show.html.twig', [
            'zone' => $zone,
            'betails' => $betails,
            'batiment' => $zone->getBatiment(),
            'ferme' => $zone->getFerme(),
            'effectifActuel' => $zone->getEffectifActuel(),
            'tauxOccupation' => $zone->getTauxOccupation(),
        ]);
    }

    #[Route('/disponibles/batiment/{id}', name: 'zone_disponibles')]
    public function zonesDisponibles(
        Batiment $batiment,
        ZoneRepository $zoneRepository
    ): Response {
        $zonesDisponibles = $zoneRepository->findAvailableByBatiment($batiment);
        
        return $this->render('zone/disponibles.html.twig', [
            'batiment' => $batiment,
            'zones' => $zonesDisponibles,
        ]);
    }
}