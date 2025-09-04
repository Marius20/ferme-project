<?php

namespace App\Repository;

use App\Entity\FamilleBetail;
use App\Entity\Ferme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FamilleBetail>
 */
class FamilleBetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FamilleBetail::class);
    }

    /**
     * Retourne toutes les familles disponibles (table de référence)
     */
    public function findAllDisponibles(): array
    {
        return $this->createQueryBuilder('f')
            ->orderBy('f.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve une famille par nom
     */
    public function findByNom(string $nom): ?FamilleBetail
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.nom = :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Retourne les familles non encore associées à une ferme donnée
     */
    public function findNonAssocieesAFerme(Ferme $ferme): array
    {
        return $this->createQueryBuilder('f')
            ->leftJoin('f.familleFermes', 'ff', 'WITH', 'ff.ferme = :ferme AND ff.actif = :actif')
            ->andWhere('ff.id IS NULL')
            ->setParameter('ferme', $ferme)
            ->setParameter('actif', true)
            ->orderBy('f.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Crée les familles de base si elles n'existent pas
     */
    public function createDefaultFamilies(): void
    {
        $defaultFamilies = [
            'Bovins' => 'Vaches, bœufs, taureaux - Élevage pour la production laitière et la viande',
            'Ovins' => 'Moutons, brebis, béliers - Élevage pour la laine, la viande et parfois le lait',
            'Caprins' => 'Chèvres, boucs - Élevage pour le lait, la viande et parfois la fibre',
            'Porcins' => 'Porcs, truies, verrats - Élevage pour la production de viande',
            'Équins' => 'Chevaux, ânes - Utilisés pour le travail, le transport et les loisirs',
            'Volailles' => 'Poules, coqs, canards, oies - Élevage pour les œufs et la viande'
        ];

        foreach ($defaultFamilies as $nom => $description) {
            if (!$this->findByNom($nom)) {
                $famille = new FamilleBetail();
                $famille->setNom($nom)
                       ->setDescription($description);
                
                $this->getEntityManager()->persist($famille);
            }
        }

        $this->getEntityManager()->flush();
    }

    public function save(FamilleBetail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FamilleBetail $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}