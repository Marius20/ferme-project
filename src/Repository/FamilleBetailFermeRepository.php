<?php

namespace App\Repository;

use App\Entity\FamilleBetailFerme;
use App\Entity\FamilleBetail;
use App\Entity\Ferme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FamilleBetailFerme>
 */
class FamilleBetailFermeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FamilleBetailFerme::class);
    }

    /**
     * Trouve les familles de bétail associées à une ferme
     */
    public function findActiveByFerme(Ferme $ferme): array
    {
        return $this->createQueryBuilder('ff')
            ->innerJoin('ff.famille', 'f')
            ->andWhere('ff.ferme = :ferme')
            ->andWhere('ff.actif = :actif')
            ->setParameter('ferme', $ferme)
            ->setParameter('actif', true)
            ->orderBy('ff.dateAjout', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve une association famille-ferme spécifique
     */
    public function findByFamilleAndFerme(FamilleBetail $famille, Ferme $ferme): ?FamilleBetailFerme
    {
        return $this->createQueryBuilder('ff')
            ->andWhere('ff.famille = :famille')
            ->andWhere('ff.ferme = :ferme')
            ->andWhere('ff.actif = :actif')
            ->setParameter('famille', $famille)
            ->setParameter('ferme', $ferme)
            ->setParameter('actif', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne les statistiques des familles pour une ferme avec effectifs
     */
    public function getStatistiquesParFerme(Ferme $ferme): array
    {
        return $this->createQueryBuilder('ff')
            ->select([
                'ff.id',
                'f.nom as nomFamille',
                'f.description as descriptionFamille',
                'ff.descriptifPersonnalise',
                'SUM(t.effectif) as effectifTotal'
            ])
            ->innerJoin('ff.famille', 'f')
            ->leftJoin('ff.typesBetail', 't', 'WITH', 't.actif = :actif')
            ->andWhere('ff.ferme = :ferme')
            ->andWhere('ff.actif = :actif')
            ->setParameter('ferme', $ferme)
            ->setParameter('actif', true)
            ->groupBy('ff.id', 'f.nom', 'f.description', 'ff.descriptifPersonnalise')
            ->orderBy('ff.dateAjout', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si une famille est déjà associée à une ferme
     */
    public function existePourFerme(FamilleBetail $famille, Ferme $ferme): bool
    {
        $count = $this->createQueryBuilder('ff')
            ->select('COUNT(ff.id)')
            ->andWhere('ff.famille = :famille')
            ->andWhere('ff.ferme = :ferme')
            ->andWhere('ff.actif = :actif')
            ->setParameter('famille', $famille)
            ->setParameter('ferme', $ferme)
            ->setParameter('actif', true)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    /**
     * Retourne les IDs des familles déjà associées à une ferme
     */
    public function getFamillesAssocieesIds(Ferme $ferme): array
    {
        $result = $this->createQueryBuilder('ff')
            ->select('f.id')
            ->innerJoin('ff.famille', 'f')
            ->andWhere('ff.ferme = :ferme')
            ->andWhere('ff.actif = :actif')
            ->setParameter('ferme', $ferme)
            ->setParameter('actif', true)
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'id');
    }

    public function save(FamilleBetailFerme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FamilleBetailFerme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}