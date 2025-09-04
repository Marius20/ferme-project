<?php

namespace App\Repository;

use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employe>
 */
class EmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employe::class);
    }

    public function findActifsByFerme(int $fermeId): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.ferme = :fermeId')
            ->andWhere('e.statut = :statut')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('statut', 'actif')
            ->orderBy('e.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getTachesEnRetard(int $fermeId): array
    {
        $aujourd_hui = new \DateTime();
        
        return $this->getEntityManager()
            ->createQuery('
                SELECT e.prenom, e.nom, COUNT(t.id) as tachesEnRetard
                FROM App\Entity\Employe e
                JOIN e.taches t
                WHERE e.ferme = :fermeId
                AND e.statut = :statut
                AND t.datePrevue < :aujourdhui
                AND t.statut != :termine
                GROUP BY e.id
                HAVING COUNT(t.id) > 0
            ')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('statut', 'actif')
            ->setParameter('aujourdhui', $aujourd_hui)
            ->setParameter('termine', 'terminee')
            ->getResult();
    }
}