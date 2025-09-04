<?php

namespace App\Repository;

use App\Entity\ProductionOeuf;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductionOeufRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionOeuf::class);
    }

    public function getProductionMensuelle(int $fermeId, \DateTime $mois): array
    {
        $debutMois = clone $mois;
        $debutMois->modify('first day of this month');
        $finMois = clone $mois;
        $finMois->modify('last day of this month');
        
        return $this->getEntityManager()
            ->createQuery('
                SELECT DATE(po.date) as jour, SUM(po.quantite) as total
                FROM App\Entity\ProductionOeuf po
                JOIN po.volaille v
                WHERE v.ferme = :fermeId
                AND po.date BETWEEN :debut AND :fin
                GROUP BY DATE(po.date)
                ORDER BY po.date ASC
            ')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('debut', $debutMois)
            ->setParameter('fin', $finMois)
            ->getResult();
    }

    public function getProductionByPeriod(int $fermeId, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT po.date, SUM(po.quantite) as quantite
                FROM App\Entity\ProductionOeuf po
                JOIN po.volaille v
                WHERE v.ferme = :fermeId
                AND po.date BETWEEN :debut AND :fin
                GROUP BY po.date
                ORDER BY po.date ASC
            ')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin)
            ->getResult();
    }

    public function getProductionStatistics(): array
    {
        $today = new \DateTime();
        $result = $this->getEntityManager()
            ->createQuery('
                SELECT SUM(po.quantite) as aujourd_hui
                FROM App\Entity\ProductionOeuf po
                WHERE DATE(po.date) = :today
            ')
            ->setParameter('today', $today->format('Y-m-d'))
            ->getOneOrNullResult();

        return [
            'aujourd_hui' => $result['aujourd_hui'] ?? 0
        ];
    }
}