<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    public function getRecettesMensuellesParCategorie(int $fermeId, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.categorie, SUM(t.montant) as total')
            ->where('t.ferme = :fermeId')
            ->andWhere('t.type = :type')
            ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('type', 'recette')
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin)
            ->groupBy('t.categorie')
            ->getQuery()
            ->getResult();
    }

    public function getDepensesMensuellesParCategorie(int $fermeId, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.categorie, SUM(t.montant) as total')
            ->where('t.ferme = :fermeId')
            ->andWhere('t.type = :type')
            ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('type', 'depense')
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin)
            ->groupBy('t.categorie')
            ->getQuery()
            ->getResult();
    }

    public function getBeneficesMensuels(int $fermeId, int $nombreMois = 12): array
    {
        $resultats = [];
        
        for ($i = $nombreMois - 1; $i >= 0; $i--) {
            $date = new \DateTime("-{$i} months");
            $debutMois = clone $date;
            $debutMois->modify('first day of this month 00:00:00');
            $finMois = clone $date;
            $finMois->modify('last day of this month 23:59:59');
            
            $recettes = $this->createQueryBuilder('t')
                ->select('COALESCE(SUM(t.montant), 0) as total')
                ->where('t.ferme = :fermeId')
                ->andWhere('t.type = :recette')
                ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
                ->andWhere('t.statut = :statut')
                ->setParameter('fermeId', $fermeId)
                ->setParameter('recette', 'recette')
                ->setParameter('debut', $debutMois)
                ->setParameter('fin', $finMois)
                ->setParameter('statut', 'validee')
                ->getQuery()
                ->getSingleScalarResult();
                
            $depenses = $this->createQueryBuilder('t')
                ->select('COALESCE(SUM(t.montant), 0) as total')
                ->where('t.ferme = :fermeId')
                ->andWhere('t.type = :depense')
                ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
                ->andWhere('t.statut = :statut')
                ->setParameter('fermeId', $fermeId)
                ->setParameter('depense', 'depense')
                ->setParameter('debut', $debutMois)
                ->setParameter('fin', $finMois)
                ->setParameter('statut', 'validee')
                ->getQuery()
                ->getSingleScalarResult();
                
            $resultats[] = [
                'mois' => $date->format('M Y'),
                'recettes' => (float)$recettes,
                'depenses' => (float)$depenses,
                'benefice' => (float)$recettes - (float)$depenses
            ];
        }
        
        return $resultats;
    }

    public function getTotalByTypeAndPeriod(string $type, \DateTime $dateDebut, \DateTime $dateFin): float
    {
        $result = $this->createQueryBuilder('t')
            ->select('COALESCE(SUM(t.montant), 0) as total')
            ->where('t.type = :type')
            ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
            ->setParameter('type', $type)
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin)
            ->getQuery()
            ->getSingleScalarResult();

        return (float)($result ?? 0);
    }

    public function getEvolutionFinanciere(int $mois = 6): array
    {
        $resultats = [];
        
        for ($i = $mois - 1; $i >= 0; $i--) {
            $date = new \DateTime("-{$i} months");
            $debutMois = clone $date;
            $debutMois->modify('first day of this month 00:00:00');
            $finMois = clone $date;
            $finMois->modify('last day of this month 23:59:59');
            
            $recettes = $this->getTotalByTypeAndPeriod('recette', $debutMois, $finMois);
            $depenses = $this->getTotalByTypeAndPeriod('depense', $debutMois, $finMois);
            
            $resultats[] = [
                'mois' => $date,
                'recettes' => $recettes,
                'depenses' => $depenses,
                'benefice' => $recettes - $depenses
            ];
        }
        
        return $resultats;
    }

    public function getRecettesParCategorie(\DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.categorie, SUM(t.montant) as montant')
            ->where('t.type = :type')
            ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
            ->setParameter('type', 'recette')
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin)
            ->groupBy('t.categorie')
            ->orderBy('montant', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getDepensesParCategorie(\DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->createQueryBuilder('t')
            ->select('t.categorie, SUM(t.montant) as montant')
            ->where('t.type = :type')
            ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
            ->setParameter('type', 'depense')
            ->setParameter('debut', $dateDebut)
            ->setParameter('fin', $dateFin)
            ->groupBy('t.categorie')
            ->orderBy('montant', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTotalRecettesMoisByFerme($ferme): float
    {
        $debutMois = new \DateTime('first day of this month');
        $finMois = new \DateTime('last day of this month');

        $result = $this->createQueryBuilder('t')
            ->select('COALESCE(SUM(t.montant), 0)')
            ->where('t.ferme = :ferme')
            ->andWhere('t.type = :type')
            ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
            ->andWhere('t.statut = :statut')
            ->setParameter('ferme', $ferme)
            ->setParameter('type', 'recette')
            ->setParameter('debut', $debutMois)
            ->setParameter('fin', $finMois)
            ->setParameter('statut', 'validee')
            ->getQuery()
            ->getSingleScalarResult();

        return (float)($result ?? 0);
    }

    public function getTotalDepensesMoisByFerme($ferme): float
    {
        $debutMois = new \DateTime('first day of this month');
        $finMois = new \DateTime('last day of this month');

        $result = $this->createQueryBuilder('t')
            ->select('COALESCE(SUM(t.montant), 0)')
            ->where('t.ferme = :ferme')
            ->andWhere('t.type = :type')
            ->andWhere('t.dateTransaction BETWEEN :debut AND :fin')
            ->andWhere('t.statut = :statut')
            ->setParameter('ferme', $ferme)
            ->setParameter('type', 'depense')
            ->setParameter('debut', $debutMois)
            ->setParameter('fin', $finMois)
            ->setParameter('statut', 'validee')
            ->getQuery()
            ->getSingleScalarResult();

        return (float)($result ?? 0);
    }

    public function findRecentByFerme($ferme, int $limit = 5): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.ferme = :ferme')
            ->setParameter('ferme', $ferme)
            ->orderBy('t.dateTransaction', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}