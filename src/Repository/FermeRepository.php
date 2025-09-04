<?php

namespace App\Repository;

use App\Entity\Ferme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ferme>
 */
class FermeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ferme::class);
    }

    /**
     * Récupère les statistiques globales d'une ferme
     */
    public function getStatistiquesGlobales(int $fermeId): array
    {
        $em = $this->getEntityManager();

        // Effectifs animaux par famille et type
        $effectifsAnimaux = $em->createQuery('
            SELECT f.nom as famille, tb.nom as type, COUNT(b.id) as nombre
            FROM App\Entity\Betail b
            LEFT JOIN b.typeBetail tb
            LEFT JOIN tb.familleFerme ff
            LEFT JOIN ff.famille f
            WHERE b.ferme = :fermeId AND b.dateSortie IS NULL
            GROUP BY f.nom, tb.nom
        ')
        ->setParameter('fermeId', $fermeId)
        ->getResult();

        // Effectifs volailles par type
        $effectifsVolailles = $em->createQuery('
            SELECT v.type, SUM(v.effectif) as nombre
            FROM App\Entity\Volaille v
            WHERE v.ferme = :fermeId AND v.effectif > 0
            GROUP BY v.type
        ')
        ->setParameter('fermeId', $fermeId)
        ->getResult();

        // Stocks critiques
        $stocksCritiques = $em->createQuery('
            SELECT COUNT(s.id) as nombre
            FROM App\Entity\Stock s
            WHERE s.ferme = :fermeId AND s.quantiteActuelle <= s.quantiteMinimum
        ')
        ->setParameter('fermeId', $fermeId)
        ->getSingleScalarResult();

        // Production d'œufs du jour
        $productionOeufsJour = $em->createQuery('
            SELECT SUM(po.nombreOeufs) as total
            FROM App\Entity\ProductionOeuf po
            JOIN po.volaille v
            WHERE v.ferme = :fermeId AND po.date = CURRENT_DATE()
        ')
        ->setParameter('fermeId', $fermeId)
        ->getSingleScalarResult();

        // Chiffre d'affaires du mois
        $debutMois = new \DateTime('first day of this month');
        $recettesMois = $em->createQuery('
            SELECT SUM(t.montant) as total
            FROM App\Entity\Transaction t
            WHERE t.ferme = :fermeId 
            AND t.type = \'recette\' 
            AND t.dateTransaction >= :debutMois
            AND t.statut = \'validee\'
        ')
        ->setParameter('fermeId', $fermeId)
        ->setParameter('debutMois', $debutMois)
        ->getSingleScalarResult();

        // Dépenses du mois
        $depensesMois = $em->createQuery('
            SELECT SUM(t.montant) as total
            FROM App\Entity\Transaction t
            WHERE t.ferme = :fermeId 
            AND t.type = \'depense\' 
            AND t.dateTransaction >= :debutMois
            AND t.statut = \'validee\'
        ')
        ->setParameter('fermeId', $fermeId)
        ->setParameter('debutMois', $debutMois)
        ->getSingleScalarResult();

        return [
            'effectifs' => [
                'animaux' => $effectifsAnimaux,
                'volailles' => $effectifsVolailles,
            ],
            'stocks_critiques' => $stocksCritiques ?: 0,
            'production_oeufs_jour' => $productionOeufsJour ?: 0,
            'recettes_mois' => $recettesMois ?: 0,
            'depenses_mois' => $depensesMois ?: 0,
            'benefice_mois' => ($recettesMois ?: 0) - ($depensesMois ?: 0),
        ];
    }

    /**
     * Récupère les alertes d'une ferme
     */
    public function getAlertes(int $fermeId): array
    {
        $em = $this->getEntityManager();

        $alertes = [];

        // Stocks en rupture
        $stocksRupture = $em->createQuery('
            SELECT s.nom, s.quantiteActuelle, s.quantiteMinimum, s.unite
            FROM App\Entity\Stock s
            WHERE s.ferme = :fermeId AND s.quantiteActuelle <= s.quantiteMinimum
        ')
        ->setParameter('fermeId', $fermeId)
        ->getResult();

        foreach ($stocksRupture as $stock) {
            $alertes[] = [
                'type' => 'stock_critique',
                'message' => sprintf('Stock critique: %s (%s %s restants)', 
                    $stock['nom'], $stock['quantiteActuelle'], $stock['unite']),
                'priorite' => 'haute'
            ];
        }

        // Produits bientôt périmés (dans 7 jours)
        $dateLimite = new \DateTime('+7 days');
        $stocksPerimes = $em->createQuery('
            SELECT s.nom, s.datePeremption
            FROM App\Entity\Stock s
            WHERE s.ferme = :fermeId 
            AND s.datePeremption IS NOT NULL 
            AND s.datePeremption <= :dateLimite
            AND s.quantiteActuelle > 0
        ')
        ->setParameter('fermeId', $fermeId)
        ->setParameter('dateLimite', $dateLimite)
        ->getResult();

        foreach ($stocksPerimes as $stock) {
            $alertes[] = [
                'type' => 'peremption',
                'message' => sprintf('Produit bientôt périmé: %s (péremption: %s)', 
                    $stock['nom'], $stock['datePeremption']->format('d/m/Y')),
                'priorite' => 'moyenne'
            ];
        }

        // Rappels de soins vétérinaires
        $rappelsVeto = $em->createQuery('
            SELECT sv.description, sv.dateRappel, b.numeroIdentification
            FROM App\Entity\SoinVeterinaire sv
            JOIN sv.betail b
            WHERE b.ferme = :fermeId 
            AND sv.dateRappel IS NOT NULL 
            AND sv.dateRappel <= CURRENT_DATE()
        ')
        ->setParameter('fermeId', $fermeId)
        ->getResult();

        foreach ($rappelsVeto as $rappel) {
            $alertes[] = [
                'type' => 'soin_veterinaire',
                'message' => sprintf('Rappel soin vétérinaire: %s pour %s', 
                    $rappel['description'], $rappel['numeroIdentification']),
                'priorite' => 'haute'
            ];
        }

        return $alertes;
    }

    /**
     * Calcule la rentabilité par activité
     */
    public function getRentabiliteParActivite(int $fermeId, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        $em = $this->getEntityManager();

        // Recettes par catégorie
        $recettes = $em->createQuery('
            SELECT t.categorie, SUM(t.montant) as montant
            FROM App\Entity\Transaction t
            WHERE t.ferme = :fermeId 
            AND t.type = \'recette\'
            AND t.dateTransaction BETWEEN :dateDebut AND :dateFin
            AND t.statut = \'validee\'
            GROUP BY t.categorie
        ')
        ->setParameter('fermeId', $fermeId)
        ->setParameter('dateDebut', $dateDebut)
        ->setParameter('dateFin', $dateFin)
        ->getResult();

        // Dépenses par catégorie
        $depenses = $em->createQuery('
            SELECT t.categorie, SUM(t.montant) as montant
            FROM App\Entity\Transaction t
            WHERE t.ferme = :fermeId 
            AND t.type = \'depense\'
            AND t.dateTransaction BETWEEN :dateDebut AND :dateFin
            AND t.statut = \'validee\'
            GROUP BY t.categorie
        ')
        ->setParameter('fermeId', $fermeId)
        ->setParameter('dateDebut', $dateDebut)
        ->setParameter('dateFin', $dateFin)
        ->getResult();

        return [
            'recettes' => $recettes,
            'depenses' => $depenses
        ];
    }

    /**
     * Évolution des effectifs sur les 12 derniers mois
     */
    public function getEvolutionEffectifs(int $fermeId): array
    {
        $em = $this->getEntityManager();
        $donnees = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = new \DateTime("-$i months");
            $finMois = clone $date;
            $finMois->modify('last day of this month');

            // Animaux actifs à cette date
            $effectifAnimaux = $em->createQuery('
                SELECT f.nom as famille, COUNT(b.id) as nombre
                FROM App\Entity\Betail b
                LEFT JOIN b.typeBetail tb
                LEFT JOIN tb.familleFerme ff
                LEFT JOIN ff.famille f
                WHERE b.ferme = :fermeId 
                AND b.dateEntree <= :date
                AND (b.dateSortie IS NULL OR b.dateSortie > :date)
                GROUP BY f.nom
            ')
            ->setParameter('fermeId', $fermeId)
            ->setParameter('date', $finMois)
            ->getResult();

            $donnees[$date->format('Y-m')] = [
                'animaux' => $effectifAnimaux,
                'date' => $date->format('M Y')
            ];
        }

        return $donnees;
    }
}