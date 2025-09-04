<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831150046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE famille_betail_ferme (id INT AUTO_INCREMENT NOT NULL, ferme_id INT NOT NULL, famille_id INT NOT NULL, descriptif_personnalise LONGTEXT DEFAULT NULL, date_ajout DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', actif TINYINT(1) NOT NULL, INDEX IDX_2ECD6F1518981132 (ferme_id), INDEX IDX_2ECD6F1597A77B84 (famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE famille_betail_ferme ADD CONSTRAINT FK_2ECD6F1518981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id)');
        $this->addSql('ALTER TABLE famille_betail_ferme ADD CONSTRAINT FK_2ECD6F1597A77B84 FOREIGN KEY (famille_id) REFERENCES famille_betail (id)');
        $this->addSql('ALTER TABLE famille_betail DROP FOREIGN KEY FK_9E22E73A18981132');
        $this->addSql('DROP INDEX IDX_9E22E73A18981132 ON famille_betail');
        $this->addSql('ALTER TABLE famille_betail DROP ferme_id, DROP type, DROP date_creation, DROP actif');
        $this->addSql('ALTER TABLE type_betail DROP FOREIGN KEY FK_DB88F7B68BE28127');
        $this->addSql('DROP INDEX IDX_DB88F7B68BE28127 ON type_betail');
        $this->addSql('ALTER TABLE type_betail ADD effectif INT NOT NULL, DROP sous_type, CHANGE famille_betail_id famille_ferme_id INT NOT NULL');
        $this->addSql('ALTER TABLE type_betail ADD CONSTRAINT FK_DB88F7B65FE6B447 FOREIGN KEY (famille_ferme_id) REFERENCES famille_betail_ferme (id)');
        $this->addSql('CREATE INDEX IDX_DB88F7B65FE6B447 ON type_betail (famille_ferme_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_betail DROP FOREIGN KEY FK_DB88F7B65FE6B447');
        $this->addSql('ALTER TABLE famille_betail_ferme DROP FOREIGN KEY FK_2ECD6F1518981132');
        $this->addSql('ALTER TABLE famille_betail_ferme DROP FOREIGN KEY FK_2ECD6F1597A77B84');
        $this->addSql('DROP TABLE famille_betail_ferme');
        $this->addSql('ALTER TABLE famille_betail ADD ferme_id INT NOT NULL, ADD type VARCHAR(50) NOT NULL, ADD date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD actif TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE famille_betail ADD CONSTRAINT FK_9E22E73A18981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9E22E73A18981132 ON famille_betail (ferme_id)');
        $this->addSql('DROP INDEX IDX_DB88F7B65FE6B447 ON type_betail');
        $this->addSql('ALTER TABLE type_betail ADD famille_betail_id INT NOT NULL, ADD sous_type VARCHAR(50) NOT NULL, DROP famille_ferme_id, DROP effectif');
        $this->addSql('ALTER TABLE type_betail ADD CONSTRAINT FK_DB88F7B68BE28127 FOREIGN KEY (famille_betail_id) REFERENCES famille_betail (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DB88F7B68BE28127 ON type_betail (famille_betail_id)');
    }
}
