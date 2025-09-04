<?php

namespace App\Repository;

use App\Entity\Betail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Betail>
 */
class BetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Betail::class);
    }

    /**
     * Retourne tous les bétails actifs d'une ferme
     */
    public function findActiveByFerme($ferme): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.ferme = :ferme')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('ferme', $ferme)
            ->orderBy('b.numeroIdentification', 'ASC')
            ->getQuery()
            ->getResult();
    }


    /**
     * Retourne les bétails d'une zone spécifique
     */
    public function findByZone($zone): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.zone = :zone')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('zone', $zone)
            ->orderBy('b.numeroIdentification', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne les bétails d'un bâtiment spécifique
     */
    public function findByBatiment($batiment): array
    {
        return $this->createQueryBuilder('b')
            ->join('b.zone', 'z')
            ->where('z.batiment = :batiment')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('batiment', $batiment)
            ->orderBy('b.numeroIdentification', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche de bétails par critères
     */
    public function findByCriteria(array $criteria = []): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.ferme', 'f')
            ->leftJoin('b.zone', 'z')
            ->leftJoin('z.batiment', 'bat');

        if (!empty($criteria['ferme'])) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $criteria['ferme']);
        }

        if (!empty($criteria['famille'])) {
            $qb->leftJoin('b.typeBetail', 'tb_search')
               ->leftJoin('tb_search.familleFerme', 'ff_search')
               ->leftJoin('ff_search.famille', 'f_search')
               ->andWhere('f_search.nom = :famille')
               ->setParameter('famille', $criteria['famille']);
        }

        if (!empty($criteria['sexe'])) {
            $qb->andWhere('b.sexe = :sexe')
               ->setParameter('sexe', $criteria['sexe']);
        }

        if (!empty($criteria['zone'])) {
            $qb->andWhere('b.zone = :zone')
               ->setParameter('zone', $criteria['zone']);
        }

        if (!empty($criteria['batiment'])) {
            $qb->andWhere('z.batiment = :batiment')
               ->setParameter('batiment', $criteria['batiment']);
        }

        if (isset($criteria['actif']) && $criteria['actif']) {
            $qb->andWhere('b.dateSortie IS NULL');
        }

        if (!empty($criteria['search'])) {
            $qb->andWhere('b.numeroIdentification LIKE :search OR b.race LIKE :search')
               ->setParameter('search', '%' . $criteria['search'] . '%');
        }

        return $qb->orderBy('b.numeroIdentification', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    public function countActiveByFerme($ferme): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->andWhere('b.ferme = :ferme')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('ferme', $ferme)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countActiveByFamilleAndFerme($ferme): array
    {
        $result = $this->createQueryBuilder('b')
            ->select('f.nom as famille, COUNT(b.id) as count')
            ->leftJoin('b.typeBetail', 'tb')
            ->leftJoin('tb.familleFerme', 'ff')
            ->leftJoin('ff.famille', 'f')
            ->andWhere('b.ferme = :ferme')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('ferme', $ferme)
            ->groupBy('f.nom')
            ->getQuery()
            ->getArrayResult();

        $stats = [];
        foreach ($result as $row) {
            $stats[$row['famille']] = (int)$row['count'];
        }
        return $stats;
    }

    public function countAllActive(): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->andWhere('b.dateSortie IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Compte le nombre total de bétails actifs
     */
    public function countActive(): int
    {
        return $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.dateSortie IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Retourne les statistiques générales des bétails
     */
    public function getStatistics(): array
    {
        return [
            'total' => $this->countActive(),
            'by_famille' => $this->createQueryBuilder('b')
                ->select('f.nom as famille, COUNT(b.id) as count')
                ->leftJoin('b.typeBetail', 'tb')
                ->leftJoin('tb.familleFerme', 'ff')
                ->leftJoin('ff.famille', 'f')
                ->where('b.dateSortie IS NULL')
                ->groupBy('f.nom')
                ->getQuery()
                ->getResult(),
            'by_sexe' => $this->createQueryBuilder('b')
                ->select('b.sexe, COUNT(b.id) as count')
                ->where('b.dateSortie IS NULL')
                ->groupBy('b.sexe')
                ->getQuery()
                ->getResult(),
        ];
    }

    /**
     * Compte les bétails actifs par famille pour une ferme
     */
    public function countActiveByFamille(string $nomFamille, $ferme = null): int
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->leftJoin('b.typeBetail', 'tb')
            ->leftJoin('tb.familleFerme', 'ff')
            ->leftJoin('ff.famille', 'f')
            ->where('f.nom = :nomFamille')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('nomFamille', $nomFamille);

        if ($ferme) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Compte les bétails actifs par type dans une famille-ferme
     */
    public function countActiveByTypeBetail(string $nomFamille, string $nomType, $ferme = null): int
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->leftJoin('b.typeBetail', 'tb')
            ->leftJoin('tb.familleFerme', 'ff')
            ->leftJoin('ff.famille', 'f')
            ->where('f.nom = :nomFamille')
            ->andWhere('tb.nom = :nomType')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('nomFamille', $nomFamille)
            ->setParameter('nomType', $nomType);

        if ($ferme) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Retourne les bétails par famille et type
     */
    public function findByFamilleAndType(string $nomFamille, string $nomType, $ferme = null): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.zone', 'z')
            ->leftJoin('z.batiment', 'bat')
            ->leftJoin('b.mere', 'm')
            ->leftJoin('b.pere', 'p')
            ->leftJoin('b.typeBetail', 'tb')
            ->leftJoin('tb.familleFerme', 'ff')
            ->leftJoin('ff.famille', 'f')
            ->where('f.nom = :nomFamille')
            ->andWhere('tb.nom = :nomType')
            ->andWhere('b.dateSortie IS NULL')
            ->setParameter('nomFamille', $nomFamille)
            ->setParameter('nomType', $nomType);

        if ($ferme) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->orderBy('b.numeroIdentification', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    /**
     * Trouve les bétails nés dans la ferme (avec parents connus)
     */
    public function findNesDansLaFerme($ferme = null): array
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.mere', 'm')
            ->leftJoin('b.pere', 'p')
            ->where('(b.mere IS NOT NULL OR b.pere IS NOT NULL)')
            ->andWhere('b.dateSortie IS NULL');

        if ($ferme) {
            $qb->andWhere('b.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->orderBy('b.dateEntree', 'DESC')
                  ->getQuery()
                  ->getResult();
    }
}