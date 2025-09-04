<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stock>
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    /**
     * Récupère tous les stocks d'une ferme avec leurs alertes
     */
    public function findByFermeWithAlerts(int $fermeId): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.ferme = :fermeId')
            ->setParameter('fermeId', $fermeId)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les stocks en rupture ou critique
     */
    public function getStocksCritiques(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.quantite <= s.seuilAlerte')
            ->orderBy('s.quantite', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les stocks bientôt périmés
     */
    public function findStocksBientotPerimes(int $fermeId, int $jours = 7): array
    {
        $dateLimite = new \DateTime("+{$jours} days");
        
        return $this->createQueryBuilder('s')
            ->where('s.ferme = :fermeId')
            ->andWhere('s.datePeremption IS NOT NULL')
            ->andWhere('s.datePeremption <= :dateLimite')
            ->andWhere('s.quantiteActuelle > 0')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('dateLimite', $dateLimite)
            ->orderBy('s.datePeremption', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule la valeur totale des stocks d'une ferme
     */
    public function calculerValeurTotale(int $fermeId): float
    {
        $result = $this->createQueryBuilder('s')
            ->select('SUM(s.valeurStock)')
            ->where('s.ferme = :fermeId')
            ->setParameter('fermeId', $fermeId)
            ->getQuery()
            ->getSingleScalarResult();

        return (float)($result ?? 0);
    }

    /**
     * Récupère les mouvements de stock récents
     */
    public function getMouvementsRecents(int $fermeId, int $limit = 10): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT ms, s
                FROM App\Entity\MouvementStock ms
                JOIN ms.stock s
                WHERE s.ferme = :fermeId
                ORDER BY ms.dateMouvement DESC
            ')
            ->setParameter('fermeId', $fermeId)
            ->setMaxResults($limit)
            ->getResult();
    }

    /**
     * Statistiques de consommation par type d'aliment
     */
    public function getStatistiquesConsommation(int $fermeId, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->getEntityManager()
            ->createQuery('
                SELECT s.type, s.nom, SUM(ms.quantite) as quantiteConsommee, AVG(ms.prixUnitaire) as prixMoyen
                FROM App\Entity\MouvementStock ms
                JOIN ms.stock s
                WHERE s.ferme = :fermeId 
                AND ms.type = \'sortie\'
                AND ms.motif = \'distribution\'
                AND ms.dateMouvement BETWEEN :dateDebut AND :dateFin
                GROUP BY s.type, s.nom
                ORDER BY quantiteConsommee DESC
            ')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('dateDebut', $dateDebut)
            ->setParameter('dateFin', $dateFin)
            ->getResult();
    }

    /**
     * Consommation journalière moyenne par stock
     */
    public function getConsommationJournaliereMoyenne(int $stockId, int $nombreJours = 30): float
    {
        $dateDebut = new \DateTime("-{$nombreJours} days");
        
        $result = $this->getEntityManager()
            ->createQuery('
                SELECT SUM(ms.quantite) / :nombreJours as moyenne
                FROM App\Entity\MouvementStock ms
                WHERE ms.stock = :stockId
                AND ms.type = \'sortie\'
                AND ms.motif = \'distribution\'
                AND ms.dateMouvement >= :dateDebut
            ')
            ->setParameter('stockId', $stockId)
            ->setParameter('nombreJours', $nombreJours)
            ->setParameter('dateDebut', $dateDebut)
            ->getSingleScalarResult();

        return (float)($result ?? 0);
    }

    /**
     * Estimation du nombre de jours restants pour un stock
     */
    public function estimerJoursRestants(int $stockId): ?int
    {
        $stock = $this->find($stockId);
        if (!$stock) {
            return null;
        }

        $consommationMoyenne = $this->getConsommationJournaliereMoyenne($stockId);
        
        if ($consommationMoyenne <= 0) {
            return null;
        }

        return (int)floor((float)$stock->getQuantiteActuelle() / $consommationMoyenne);
    }

    /**
     * Récupère les stocks par type avec leurs quantités
     */
    public function findByTypeWithQuantities(int $fermeId, string $type): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.ferme = :fermeId')
            ->andWhere('s.type = :type')
            ->andWhere('s.quantiteActuelle > 0')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('type', $type)
            ->orderBy('s.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}