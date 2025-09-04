<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250829210807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alimentations (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, stock_id INT NOT NULL, employe_id INT DEFAULT NULL, date DATE NOT NULL, quantite NUMERIC(10, 3) NOT NULL, quantite_prevue NUMERIC(10, 3) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_CD56093C8E962C16 (animal_id), INDEX IDX_CD56093CDCD6110 (stock_id), INDEX IDX_CD56093C1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE alimentations_volaille (id INT AUTO_INCREMENT NOT NULL, volaille_id INT NOT NULL, stock_id INT NOT NULL, employe_id INT DEFAULT NULL, date DATE NOT NULL, quantite NUMERIC(10, 3) NOT NULL, quantite_prevue NUMERIC(10, 3) DEFAULT NULL, effectif_lors INT NOT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_4E0D6D7553C8E37C (volaille_id), INDEX IDX_4E0D6D75DCD6110 (stock_id), INDEX IDX_4E0D6D751B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE animaux (id INT AUTO_INCREMENT NOT NULL, ferme_id INT NOT NULL, type VARCHAR(50) NOT NULL, sous_type VARCHAR(50) NOT NULL, race VARCHAR(100) DEFAULT NULL, mode VARCHAR(50) NOT NULL, numero_identification VARCHAR(100) NOT NULL, photo VARCHAR(500) DEFAULT NULL, sexe VARCHAR(10) NOT NULL, age INT DEFAULT NULL, date_entree DATE NOT NULL, poids_entree NUMERIC(8, 2) DEFAULT NULL, date_sortie DATE DEFAULT NULL, type_sortie VARCHAR(50) DEFAULT NULL, poids_sortie NUMERIC(8, 2) DEFAULT NULL, prix_vente NUMERIC(10, 2) DEFAULT NULL, acheteur VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_9ABE194DF5211ED (numero_identification), INDEX IDX_9ABE194D18981132 (ferme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employes (id INT AUTO_INCREMENT NOT NULL, ferme_id INT NOT NULL, prenom VARCHAR(100) NOT NULL, nom VARCHAR(100) NOT NULL, email VARCHAR(180) DEFAULT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse LONGTEXT DEFAULT NULL, role VARCHAR(50) NOT NULL, statut VARCHAR(20) NOT NULL, salaire_mensuel NUMERIC(10, 2) DEFAULT NULL, date_embauche DATE DEFAULT NULL, date_fin_contrat DATE DEFAULT NULL, competences LONGTEXT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A94BC0F0E7927C74 (email), INDEX IDX_A94BC0F018981132 (ferme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fermes (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, localisation VARCHAR(500) DEFAULT NULL, superficie NUMERIC(10, 2) DEFAULT NULL, type VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mouvements_stock (id INT AUTO_INCREMENT NOT NULL, stock_id INT NOT NULL, employe_id INT DEFAULT NULL, type VARCHAR(20) NOT NULL, motif VARCHAR(50) NOT NULL, quantite NUMERIC(12, 3) NOT NULL, prix_unitaire NUMERIC(10, 2) DEFAULT NULL, montant_total NUMERIC(12, 2) DEFAULT NULL, fournisseur VARCHAR(255) DEFAULT NULL, numero_facture VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_mouvement DATETIME NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_B3536722DCD6110 (stock_id), INDEX IDX_B35367221B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE presences_employes (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, date DATE NOT NULL, heure_arrivee TIME DEFAULT NULL, heure_depart TIME DEFAULT NULL, pause_debut TIME DEFAULT NULL, pause_fin TIME DEFAULT NULL, heures_travaillees NUMERIC(5, 2) DEFAULT NULL, heures_supplementaires NUMERIC(5, 2) DEFAULT NULL, type VARCHAR(20) NOT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_B41AF9781B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productions_oeufs (id INT AUTO_INCREMENT NOT NULL, volaille_id INT NOT NULL, employe_id INT DEFAULT NULL, date DATE NOT NULL, nombre_oeufs INT NOT NULL, nombre_extra INT DEFAULT NULL, nombre_premiere INT DEFAULT NULL, nombre_seconde INT DEFAULT NULL, nombre_dechet INT DEFAULT NULL, poids_moyen NUMERIC(8, 2) DEFAULT NULL, effectif_lors INT NOT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_34AC7D8553C8E37C (volaille_id), INDEX IDX_34AC7D851B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reproductions (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, type VARCHAR(20) NOT NULL, date_reproduction DATE NOT NULL, date_gestation DATE DEFAULT NULL, date_mise_bas DATE DEFAULT NULL, statut VARCHAR(20) NOT NULL, nombre_naissances INT DEFAULT NULL, nombre_vivants INT DEFAULT NULL, nombre_morts INT DEFAULT NULL, pere VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_B57DE57B8E962C16 (animal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soins_veterinaires (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, employe_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, date_soin DATE NOT NULL, veterinaire VARCHAR(255) DEFAULT NULL, medicament VARCHAR(255) DEFAULT NULL, dosage NUMERIC(8, 3) DEFAULT NULL, unite_dosage VARCHAR(50) DEFAULT NULL, cout NUMERIC(10, 2) DEFAULT NULL, date_rappel DATE DEFAULT NULL, delai_attente INT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_A799A3118E962C16 (animal_id), INDEX IDX_A799A3111B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soins_veterinaires_volaille (id INT AUTO_INCREMENT NOT NULL, volaille_id INT NOT NULL, employe_id INT DEFAULT NULL, type VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, date_soin DATE NOT NULL, veterinaire VARCHAR(255) DEFAULT NULL, medicament VARCHAR(255) DEFAULT NULL, dosage_total_lot NUMERIC(10, 3) DEFAULT NULL, unite_dosage VARCHAR(50) DEFAULT NULL, effectif_traite INT NOT NULL, cout NUMERIC(10, 2) DEFAULT NULL, date_rappel DATE DEFAULT NULL, delai_attente INT DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_9504309B53C8E37C (volaille_id), INDEX IDX_9504309B1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stocks (id INT AUTO_INCREMENT NOT NULL, ferme_id INT NOT NULL, nom VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, unite VARCHAR(20) NOT NULL, quantite_actuelle NUMERIC(12, 3) NOT NULL, quantite_minimum NUMERIC(12, 3) NOT NULL, quantite_maximum NUMERIC(12, 3) DEFAULT NULL, prix_unitaire_moyen NUMERIC(10, 2) DEFAULT NULL, valeur_stock NUMERIC(12, 2) DEFAULT NULL, emplacement VARCHAR(255) DEFAULT NULL, date_peremption DATE DEFAULT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, INDEX IDX_56F7980518981132 (ferme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taches_employes (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, animal_id INT DEFAULT NULL, volaille_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(50) NOT NULL, priorite VARCHAR(20) NOT NULL, statut VARCHAR(20) NOT NULL, date_prevue DATETIME NOT NULL, duree_estimee INT DEFAULT NULL, date_debut DATETIME DEFAULT NULL, date_fin DATETIME DEFAULT NULL, duree_reelle INT DEFAULT NULL, commentaires LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_8A39EBCE1B65292 (employe_id), INDEX IDX_8A39EBCE8E962C16 (animal_id), INDEX IDX_8A39EBCE53C8E37C (volaille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactions (id INT AUTO_INCREMENT NOT NULL, ferme_id INT NOT NULL, employe_id INT DEFAULT NULL, animal_id INT DEFAULT NULL, volaille_id INT DEFAULT NULL, stock_id INT DEFAULT NULL, type VARCHAR(20) NOT NULL, categorie VARCHAR(50) NOT NULL, montant NUMERIC(12, 2) NOT NULL, description VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, tiers VARCHAR(255) DEFAULT NULL, numero_facture VARCHAR(100) DEFAULT NULL, mode_paiement VARCHAR(50) DEFAULT NULL, statut VARCHAR(20) NOT NULL, date_transaction DATE NOT NULL, date_echeance DATE DEFAULT NULL, date_creation DATETIME NOT NULL, date_modification DATETIME DEFAULT NULL, INDEX IDX_EAA81A4C18981132 (ferme_id), INDEX IDX_EAA81A4C1B65292 (employe_id), INDEX IDX_EAA81A4C8E962C16 (animal_id), INDEX IDX_EAA81A4C53C8E37C (volaille_id), INDEX IDX_EAA81A4CDCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ventes (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, employe_id INT DEFAULT NULL, date_vente DATE NOT NULL, poids NUMERIC(8, 2) DEFAULT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, montant_total NUMERIC(12, 2) NOT NULL, acheteur VARCHAR(255) NOT NULL, contact_acheteur VARCHAR(255) DEFAULT NULL, statut VARCHAR(20) NOT NULL, date_livraison DATE DEFAULT NULL, date_paiement DATE DEFAULT NULL, mode_paiement VARCHAR(100) DEFAULT NULL, numero_facture VARCHAR(100) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_64EC489A8E962C16 (animal_id), INDEX IDX_64EC489A1B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ventes_volaille (id INT AUTO_INCREMENT NOT NULL, volaille_id INT NOT NULL, employe_id INT DEFAULT NULL, date_vente DATE NOT NULL, quantite INT NOT NULL, poids_unitaire NUMERIC(8, 2) DEFAULT NULL, poids_total NUMERIC(10, 2) DEFAULT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, montant_total NUMERIC(12, 2) NOT NULL, acheteur VARCHAR(255) NOT NULL, contact_acheteur VARCHAR(255) DEFAULT NULL, statut VARCHAR(20) NOT NULL, date_livraison DATE DEFAULT NULL, date_paiement DATE DEFAULT NULL, mode_paiement VARCHAR(100) DEFAULT NULL, numero_facture VARCHAR(100) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_869204A853C8E37C (volaille_id), INDEX IDX_869204A81B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volailles (id INT AUTO_INCREMENT NOT NULL, ferme_id INT NOT NULL, type VARCHAR(50) NOT NULL, race VARCHAR(100) DEFAULT NULL, mode VARCHAR(50) NOT NULL, numero_lot VARCHAR(100) NOT NULL, effectif_initial INT NOT NULL, effectif INT NOT NULL, date_entree DATE NOT NULL, age_entree INT DEFAULT NULL, date_sortie DATE DEFAULT NULL, type_sortie VARCHAR(50) DEFAULT NULL, effectif_sortie INT DEFAULT NULL, poids_unitaire_moyen NUMERIC(8, 2) DEFAULT NULL, prix_unitaire NUMERIC(10, 2) DEFAULT NULL, acheteur VARCHAR(255) DEFAULT NULL, notes LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_965C362F18981132 (ferme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alimentations ADD CONSTRAINT FK_CD56093C8E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE alimentations ADD CONSTRAINT FK_CD56093CDCD6110 FOREIGN KEY (stock_id) REFERENCES stocks (id)');
        $this->addSql('ALTER TABLE alimentations ADD CONSTRAINT FK_CD56093C1B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE alimentations_volaille ADD CONSTRAINT FK_4E0D6D7553C8E37C FOREIGN KEY (volaille_id) REFERENCES volailles (id)');
        $this->addSql('ALTER TABLE alimentations_volaille ADD CONSTRAINT FK_4E0D6D75DCD6110 FOREIGN KEY (stock_id) REFERENCES stocks (id)');
        $this->addSql('ALTER TABLE alimentations_volaille ADD CONSTRAINT FK_4E0D6D751B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE animaux ADD CONSTRAINT FK_9ABE194D18981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id)');
        $this->addSql('ALTER TABLE employes ADD CONSTRAINT FK_A94BC0F018981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id)');
        $this->addSql('ALTER TABLE mouvements_stock ADD CONSTRAINT FK_B3536722DCD6110 FOREIGN KEY (stock_id) REFERENCES stocks (id)');
        $this->addSql('ALTER TABLE mouvements_stock ADD CONSTRAINT FK_B35367221B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE presences_employes ADD CONSTRAINT FK_B41AF9781B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE productions_oeufs ADD CONSTRAINT FK_34AC7D8553C8E37C FOREIGN KEY (volaille_id) REFERENCES volailles (id)');
        $this->addSql('ALTER TABLE productions_oeufs ADD CONSTRAINT FK_34AC7D851B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE reproductions ADD CONSTRAINT FK_B57DE57B8E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE soins_veterinaires ADD CONSTRAINT FK_A799A3118E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE soins_veterinaires ADD CONSTRAINT FK_A799A3111B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE soins_veterinaires_volaille ADD CONSTRAINT FK_9504309B53C8E37C FOREIGN KEY (volaille_id) REFERENCES volailles (id)');
        $this->addSql('ALTER TABLE soins_veterinaires_volaille ADD CONSTRAINT FK_9504309B1B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE stocks ADD CONSTRAINT FK_56F7980518981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id)');
        $this->addSql('ALTER TABLE taches_employes ADD CONSTRAINT FK_8A39EBCE1B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE taches_employes ADD CONSTRAINT FK_8A39EBCE8E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE taches_employes ADD CONSTRAINT FK_8A39EBCE53C8E37C FOREIGN KEY (volaille_id) REFERENCES volailles (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C18981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C1B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C8E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C53C8E37C FOREIGN KEY (volaille_id) REFERENCES volailles (id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CDCD6110 FOREIGN KEY (stock_id) REFERENCES stocks (id)');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489A8E962C16 FOREIGN KEY (animal_id) REFERENCES animaux (id)');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489A1B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE ventes_volaille ADD CONSTRAINT FK_869204A853C8E37C FOREIGN KEY (volaille_id) REFERENCES volailles (id)');
        $this->addSql('ALTER TABLE ventes_volaille ADD CONSTRAINT FK_869204A81B65292 FOREIGN KEY (employe_id) REFERENCES employes (id)');
        $this->addSql('ALTER TABLE volailles ADD CONSTRAINT FK_965C362F18981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alimentations DROP FOREIGN KEY FK_CD56093C8E962C16');
        $this->addSql('ALTER TABLE alimentations DROP FOREIGN KEY FK_CD56093CDCD6110');
        $this->addSql('ALTER TABLE alimentations DROP FOREIGN KEY FK_CD56093C1B65292');
        $this->addSql('ALTER TABLE alimentations_volaille DROP FOREIGN KEY FK_4E0D6D7553C8E37C');
        $this->addSql('ALTER TABLE alimentations_volaille DROP FOREIGN KEY FK_4E0D6D75DCD6110');
        $this->addSql('ALTER TABLE alimentations_volaille DROP FOREIGN KEY FK_4E0D6D751B65292');
        $this->addSql('ALTER TABLE animaux DROP FOREIGN KEY FK_9ABE194D18981132');
        $this->addSql('ALTER TABLE employes DROP FOREIGN KEY FK_A94BC0F018981132');
        $this->addSql('ALTER TABLE mouvements_stock DROP FOREIGN KEY FK_B3536722DCD6110');
        $this->addSql('ALTER TABLE mouvements_stock DROP FOREIGN KEY FK_B35367221B65292');
        $this->addSql('ALTER TABLE presences_employes DROP FOREIGN KEY FK_B41AF9781B65292');
        $this->addSql('ALTER TABLE productions_oeufs DROP FOREIGN KEY FK_34AC7D8553C8E37C');
        $this->addSql('ALTER TABLE productions_oeufs DROP FOREIGN KEY FK_34AC7D851B65292');
        $this->addSql('ALTER TABLE reproductions DROP FOREIGN KEY FK_B57DE57B8E962C16');
        $this->addSql('ALTER TABLE soins_veterinaires DROP FOREIGN KEY FK_A799A3118E962C16');
        $this->addSql('ALTER TABLE soins_veterinaires DROP FOREIGN KEY FK_A799A3111B65292');
        $this->addSql('ALTER TABLE soins_veterinaires_volaille DROP FOREIGN KEY FK_9504309B53C8E37C');
        $this->addSql('ALTER TABLE soins_veterinaires_volaille DROP FOREIGN KEY FK_9504309B1B65292');
        $this->addSql('ALTER TABLE stocks DROP FOREIGN KEY FK_56F7980518981132');
        $this->addSql('ALTER TABLE taches_employes DROP FOREIGN KEY FK_8A39EBCE1B65292');
        $this->addSql('ALTER TABLE taches_employes DROP FOREIGN KEY FK_8A39EBCE8E962C16');
        $this->addSql('ALTER TABLE taches_employes DROP FOREIGN KEY FK_8A39EBCE53C8E37C');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C18981132');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C1B65292');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C8E962C16');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C53C8E37C');
        $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CDCD6110');
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489A8E962C16');
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489A1B65292');
        $this->addSql('ALTER TABLE ventes_volaille DROP FOREIGN KEY FK_869204A853C8E37C');
        $this->addSql('ALTER TABLE ventes_volaille DROP FOREIGN KEY FK_869204A81B65292');
        $this->addSql('ALTER TABLE volailles DROP FOREIGN KEY FK_965C362F18981132');
        $this->addSql('DROP TABLE alimentations');
        $this->addSql('DROP TABLE alimentations_volaille');
        $this->addSql('DROP TABLE animaux');
        $this->addSql('DROP TABLE employes');
        $this->addSql('DROP TABLE fermes');
        $this->addSql('DROP TABLE mouvements_stock');
        $this->addSql('DROP TABLE presences_employes');
        $this->addSql('DROP TABLE productions_oeufs');
        $this->addSql('DROP TABLE reproductions');
        $this->addSql('DROP TABLE soins_veterinaires');
        $this->addSql('DROP TABLE soins_veterinaires_volaille');
        $this->addSql('DROP TABLE stocks');
        $this->addSql('DROP TABLE taches_employes');
        $this->addSql('DROP TABLE transactions');
        $this->addSql('DROP TABLE ventes');
        $this->addSql('DROP TABLE ventes_volaille');
        $this->addSql('DROP TABLE volailles');
    }
}
