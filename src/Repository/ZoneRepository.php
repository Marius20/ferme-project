<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zone>
 */
class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    /**
     * Retourne toutes les zones d'un bâtiment
     */
    public function findByBatiment($batiment): array
    {
        return $this->createQueryBuilder('z')
            ->where('z.batiment = :batiment')
            ->setParameter('batiment', $batiment)
            ->orderBy('z.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les zones disponibles d'un bâtiment
     */
    public function findAvailableByBatiment($batiment): array
    {
        return $this->createQueryBuilder('z')
            ->leftJoin('z.betails', 'b', 'WITH', 'b.dateSortie IS NULL')
            ->where('z.batiment = :batiment')
            ->andWhere('z.statut = :statut')
            ->andWhere('z.capaciteMaximale IS NULL OR COUNT(b.id) < z.capaciteMaximale')
            ->setParameter('batiment', $batiment)
            ->setParameter('statut', Zone::STATUT_DISPONIBLE)
            ->groupBy('z.id')
            ->orderBy('z.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les zones d'une ferme
     */
    public function findByFerme($ferme): array
    {
        return $this->createQueryBuilder('z')
            ->join('z.batiment', 'b')
            ->where('b.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->orderBy('b.nom', 'ASC')
            ->addOrderBy('z.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche de zones par critères
     */
    public function findByCriteria(array $criteria = []): array
    {
        $qb = $this->createQueryBuilder('z')
            ->leftJoin('z.batiment', 'b')
            ->leftJoin('b.ferme', 'f');

        if (!empty($criteria['ferme'])) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $criteria['ferme']);
        }

        if (!empty($criteria['batiment'])) {
            $qb->andWhere('z.batiment = :batiment')
               ->setParameter('batiment', $criteria['batiment']);
        }

        if (!empty($criteria['type'])) {
            $qb->andWhere('z.type = :type')
               ->setParameter('type', $criteria['type']);
        }

        if (!empty($criteria['statut'])) {
            $qb->andWhere('z.statut = :statut')
               ->setParameter('statut', $criteria['statut']);
        }

        if (isset($criteria['disponible']) && $criteria['disponible']) {
            $qb->leftJoin('z.betails', 'bet', 'WITH', 'bet.dateSortie IS NULL')
               ->andWhere('z.statut = :statutDisponible')
               ->andWhere('z.capaciteMaximale IS NULL OR COUNT(bet.id) < z.capaciteMaximale')
               ->setParameter('statutDisponible', Zone::STATUT_DISPONIBLE)
               ->groupBy('z.id');
        }

        if (!empty($criteria['search'])) {
            $qb->andWhere('z.nom LIKE :search OR z.numeroIdentification LIKE :search')
               ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        return $qb->orderBy('b.nom', 'ASC')
                  ->addOrderBy('z.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Retourne les statistiques d'occupation des zones
     */
    public function getOccupationStats($ferme = null): array
    {
        $qb = $this->createQueryBuilder('z')
            ->leftJoin('z.batiment', 'b')
            ->leftJoin('z.betails', 'bet', 'WITH', 'bet.dateSortie IS NULL')
            ->select([
                'z.id',
                'z.nom',
                'z.type',
                'z.capaciteMaximale',
                'z.statut',
                'b.nom as batimentNom',
                'COUNT(bet.id) as effectifActuel'
            ]);

        if ($ferme) {
            $qb->where('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->groupBy('z.id')
                  ->orderBy('b.nom', 'ASC')
                  ->addOrderBy('z.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Retourne les zones avec des alertes de capacité
     */
    public function findWithCapacityAlerts($ferme = null, float $seuilAlerte = 90.0): array
    {
        $qb = $this->createQueryBuilder('z')
            ->leftJoin('z.batiment', 'b')
            ->leftJoin('z.betails', 'bet', 'WITH', 'bet.dateSortie IS NULL')
            ->select([
                'z',
                'b.nom as batimentNom',
                'COUNT(bet.id) as effectifActuel'
            ])
            ->where('z.capaciteMaximale IS NOT NULL')
            ->andWhere('z.capaciteMaximale > 0')
            ->having('(COUNT(bet.id) / z.capaciteMaximale * 100) >= :seuil')
            ->setParameter('seuil', $seuilAlerte);

        if ($ferme) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->groupBy('z.id')
                  ->orderBy('b.nom', 'ASC')
                  ->addOrderBy('z.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Compte le nombre de zones par statut
     */
    public function countByStatut($ferme = null): array
    {
        $qb = $this->createQueryBuilder('z')
            ->leftJoin('z.batiment', 'b')
            ->select('z.statut, COUNT(z.id) as count');

        if ($ferme) {
            $qb->where('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->groupBy('z.statut')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Retourne les zones par type
     */
    public function findByType(string $type, $ferme = null): array
    {
        $qb = $this->createQueryBuilder('z')
            ->leftJoin('z.batiment', 'b')
            ->where('z.type = :type')
            ->setParameter('type', $type);

        if ($ferme) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->orderBy('b.nom', 'ASC')
                  ->addOrderBy('z.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }
}