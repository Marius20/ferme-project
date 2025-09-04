<?php

namespace App\Repository;

use App\Entity\Batiment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Batiment>
 */
class BatimentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Batiment::class);
    }

    /**
     * Retourne tous les bâtiments actifs d'une ferme
     */
    public function findActiveByFerme($ferme): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.ferme = :ferme')
            ->andWhere('b.statut = :statut')
            ->setParameter('ferme', $ferme)
            ->setParameter('statut', Batiment::STATUT_ACTIF)
            ->orderBy('b.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne tous les bâtiments d'une ferme avec leurs zones
     */
    public function findByFermeWithZones($ferme): array
    {
        return $this->createQueryBuilder('b')
            ->leftJoin('b.zones', 'z')
            ->addSelect('z')
            ->where('b.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->orderBy('b.nom', 'ASC')
            ->addOrderBy('z.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les bâtiments par type
     */
    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.type = :type')
            ->setParameter('type', $type)
            ->orderBy('b.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche de bâtiments par critères
     */
    public function findByCriteria(array $criteria = []): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.ferme', 'f');

        if (!empty($criteria['ferme'])) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $criteria['ferme']);
        }

        if (!empty($criteria['type'])) {
            $qb->andWhere('b.type = :type')
               ->setParameter('type', $criteria['type']);
        }

        if (!empty($criteria['statut'])) {
            $qb->andWhere('b.statut = :statut')
               ->setParameter('statut', $criteria['statut']);
        }

        if (!empty($criteria['search'])) {
            $qb->andWhere('b.nom LIKE :search OR b.numeroIdentification LIKE :search')
               ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        return $qb->orderBy('b.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Retourne les statistiques d'occupation des bâtiments
     */
    public function getOccupationStats($ferme = null): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.zones', 'z')
            ->leftJoin('z.betails', 'bet', 'WITH', 'bet.dateSortie IS NULL')
            ->select([
                'b.id',
                'b.nom',
                'b.type',
                'b.capaciteMaximale',
                'COUNT(DISTINCT z.id) as nombreZones',
                'COUNT(bet.id) as effectifActuel'
            ]);

        if ($ferme) {
            $qb->where('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->groupBy('b.id')
                  ->orderBy('b.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Retourne les bâtiments avec des alertes de capacité
     */
    public function findWithCapacityAlerts($ferme = null, float $seuilAlerte = 90.0): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.zones', 'z')
            ->leftJoin('z.betails', 'bet', 'WITH', 'bet.dateSortie IS NULL')
            ->select([
                'b',
                'COUNT(bet.id) as effectifActuel'
            ])
            ->where('b.capaciteMaximale IS NOT NULL')
            ->andWhere('b.capaciteMaximale > 0')
            ->having('(COUNT(bet.id) / b.capaciteMaximale * 100) >= :seuil')
            ->setParameter('seuil', $seuilAlerte);

        if ($ferme) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->groupBy('b.id')
                  ->orderBy('b.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Compte le nombre de bâtiments par statut
     */
    public function countByStatut($ferme = null): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.statut, COUNT(b.id) as count');

        if ($ferme) {
            $qb->where('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->groupBy('b.statut')
                  ->getQuery()
                  ->getResult();
    }

    public function countByFerme($ferme): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->andWhere('b.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countByTypeAndFerme($ferme): array
    {
        $result = $this->createQueryBuilder('b')
            ->select('b.type, COUNT(b.id) as count')
            ->andWhere('b.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->groupBy('b.type')
            ->getQuery()
            ->getArrayResult();

        $stats = [];
        foreach ($result as $row) {
            $stats[$row['type']] = (int)$row['count'];
        }
        return $stats;
    }

    public function save(Batiment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Batiment $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}