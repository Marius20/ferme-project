<?php

namespace App\Repository;

use App\Entity\Volaille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Volaille>
 */
class VolailleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Volaille::class);
    }

    public function findActiveByFerme(int $fermeId): array
    {
        return $this->createQueryBuilder('v')
            ->where('v.ferme = :fermeId')
            ->andWhere('v.nombreTete > 0')
            ->setParameter('fermeId', $fermeId)
            ->orderBy('v.dateArrivee', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPondeusesByFerme(int $fermeId): array
    {
        return $this->createQueryBuilder('v')
            ->where('v.ferme = :fermeId')
            ->andWhere('v.modeProduction = :mode')
            ->andWhere('v.statut = :statut')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('mode', 'ponte')
            ->setParameter('statut', 'actif')
            ->orderBy('v.numeroLot', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getProductionOeufsJournaliere(int $fermeId, \DateTime $date): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT v.numeroLot, v.type, SUM(po.nombreOeufs) as production, v.effectif
                FROM App\Entity\Volaille v
                LEFT JOIN v.productionsOeufs po WITH po.date = :date
                WHERE v.ferme = :fermeId
                AND v.effectif > 0
                AND v.mode = :mode
                GROUP BY v.id
            ')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('date', $date)
            ->setParameter('mode', 'ponte')
            ->getResult();
    }

    public function getTauxMortaliteMoyen(int $fermeId): float
    {
        $result = $this->createQueryBuilder('v')
            ->select('AVG((v.effectifInitial - v.effectif) / v.effectifInitial * 100) as tauxMoyen')
            ->where('v.ferme = :fermeId')
            ->andWhere('v.effectifInitial > 0')
            ->setParameter('fermeId', $fermeId)
            ->getQuery()
            ->getSingleScalarResult();

        return (float)($result ?? 0);
    }

    public function countByFerme($ferme): int
    {
        return $this->createQueryBuilder('v')
            ->select('COUNT(v.id)')
            ->andWhere('v.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getTotalEffectifByFerme($ferme): int
    {
        $result = $this->createQueryBuilder('v')
            ->select('SUM(v.effectif)')
            ->andWhere('v.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->getQuery()
            ->getSingleScalarResult();

        return (int)($result ?? 0);
    }
}