<?php

namespace App\Repository;

use App\Entity\TypeBetail;
use App\Entity\FamilleBetailFerme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeBetail>
 */
class TypeBetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeBetail::class);
    }

    /**
     * Trouve les types de bétail actifs pour une famille-ferme
     */
    public function findActiveByFamilleFerme(FamilleBetailFerme $familleFerme): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.familleFerme = :familleFerme')
            ->andWhere('t.actif = :actif')
            ->setParameter('familleFerme', $familleFerme)
            ->setParameter('actif', true)
            ->orderBy('t.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve un type de bétail par nom et famille-ferme
     */
    public function findByNomAndFamilleFerme(string $nom, FamilleBetailFerme $familleFerme): ?TypeBetail
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.nom = :nom')
            ->andWhere('t.familleFerme = :familleFerme')
            ->andWhere('t.actif = :actif')
            ->setParameter('nom', $nom)
            ->setParameter('familleFerme', $familleFerme)
            ->setParameter('actif', true)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne les types déjà créés pour une famille-ferme
     */
    public function getTypesExistantsPourFamilleFerme(FamilleBetailFerme $familleFerme): array
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.nom')
            ->andWhere('t.familleFerme = :familleFerme')
            ->andWhere('t.actif = :actif')
            ->setParameter('familleFerme', $familleFerme)
            ->setParameter('actif', true)
            ->getQuery()
            ->getScalarResult();

        return array_column($result, 'nom');
    }

    /**
     * Retourne les statistiques par type pour une famille-ferme
     */
    public function getStatistiquesParFamilleFerme(FamilleBetailFerme $familleFerme): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.id', 't.nom', 't.description', 't.effectif')
            ->andWhere('t.familleFerme = :familleFerme')
            ->andWhere('t.actif = :actif')
            ->setParameter('familleFerme', $familleFerme)
            ->setParameter('actif', true)
            ->orderBy('t.dateCreation', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Vérifie si un type de bétail existe déjà pour une famille-ferme
     */
    public function existePourFamilleFerme(string $nom, FamilleBetailFerme $familleFerme): bool
    {
        $count = $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->andWhere('t.nom = :nom')
            ->andWhere('t.familleFerme = :familleFerme')
            ->andWhere('t.actif = :actif')
            ->setParameter('nom', $nom)
            ->setParameter('familleFerme', $familleFerme)
            ->setParameter('actif', true)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    public function save(TypeBetail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TypeBetail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}