<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831142528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE famille_betail (id INT AUTO_INCREMENT NOT NULL, ferme_id INT NOT NULL, type VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', actif TINYINT(1) NOT NULL, INDEX IDX_9E22E73A18981132 (ferme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_betail (id INT AUTO_INCREMENT NOT NULL, famille_betail_id INT NOT NULL, sous_type VARCHAR(50) NOT NULL, nom VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', actif TINYINT(1) NOT NULL, INDEX IDX_DB88F7B68BE28127 (famille_betail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE famille_betail ADD CONSTRAINT FK_9E22E73A18981132 FOREIGN KEY (ferme_id) REFERENCES fermes (id)');
        $this->addSql('ALTER TABLE type_betail ADD CONSTRAINT FK_DB88F7B68BE28127 FOREIGN KEY (famille_betail_id) REFERENCES famille_betail (id)');
        $this->addSql('ALTER TABLE betails ADD type_betail_id INT NOT NULL, DROP type, DROP sous_type');
        $this->addSql('ALTER TABLE betails ADD CONSTRAINT FK_11F63EB01F0C3B21 FOREIGN KEY (type_betail_id) REFERENCES type_betail (id)');
        $this->addSql('CREATE INDEX IDX_11F63EB01F0C3B21 ON betails (type_betail_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE betails DROP FOREIGN KEY FK_11F63EB01F0C3B21');
        $this->addSql('ALTER TABLE famille_betail DROP FOREIGN KEY FK_9E22E73A18981132');
        $this->addSql('ALTER TABLE type_betail DROP FOREIGN KEY FK_DB88F7B68BE28127');
        $this->addSql('DROP TABLE famille_betail');
        $this->addSql('DROP TABLE type_betail');
        $this->addSql('DROP INDEX IDX_11F63EB01F0C3B21 ON betails');
        $this->addSql('ALTER TABLE betails ADD type VARCHAR(50) NOT NULL, ADD sous_type VARCHAR(50) NOT NULL, DROP type_betail_id');
    }
}
