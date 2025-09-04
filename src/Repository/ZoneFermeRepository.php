<?php

namespace App\Repository;

use App\Entity\ZoneFerme;
use App\Entity\Ferme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ZoneFermeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneFerme::class);
    }

    public function findByFerme(Ferme $ferme): array
    {
        return $this->createQueryBuilder('zf')
            ->andWhere('zf.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->orderBy('zf.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByType(string $type, ?Ferme $ferme = null): array
    {
        $qb = $this->createQueryBuilder('zf')
            ->andWhere('zf.type = :type')
            ->setParameter('type', $type);

        if ($ferme) {
            $qb->andWhere('zf.ferme = :ferme')
               ->setParameter('ferme', $ferme);
        }

        return $qb->orderBy('zf.nom', 'ASC')
                  ->getQuery()
                  ->getResult();
    }

    public function findWithBatiments(Ferme $ferme): array
    {
        return $this->createQueryBuilder('zf')
            ->leftJoin('zf.batiments', 'b')
            ->addSelect('b')
            ->andWhere('zf.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->orderBy('zf.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countByFerme(Ferme $ferme): int
    {
        return $this->createQueryBuilder('zf')
            ->select('COUNT(zf.id)')
            ->andWhere('zf.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function save(ZoneFerme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ZoneFerme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}