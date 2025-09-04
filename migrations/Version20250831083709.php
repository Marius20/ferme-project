<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250831083709 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add parent tracking fields (mere_id, pere_id) to betails table for inbreeding prevention';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE betails ADD mere_id INT DEFAULT NULL, ADD pere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE betails ADD CONSTRAINT FK_11F63EB039DEC40E FOREIGN KEY (mere_id) REFERENCES betails (id)');
        $this->addSql('ALTER TABLE betails ADD CONSTRAINT FK_11F63EB03FD73900 FOREIGN KEY (pere_id) REFERENCES betails (id)');
        $this->addSql('CREATE INDEX IDX_11F63EB039DEC40E ON betails (mere_id)');
        $this->addSql('CREATE INDEX IDX_11F63EB03FD73900 ON betails (pere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE betails DROP FOREIGN KEY FK_11F63EB039DEC40E');
        $this->addSql('ALTER TABLE betails DROP FOREIGN KEY FK_11F63EB03FD73900');
        $this->addSql('DROP INDEX IDX_11F63EB039DEC40E ON betails');
        $this->addSql('DROP INDEX IDX_11F63EB03FD73900 ON betails');
        $this->addSql('ALTER TABLE betails DROP mere_id, DROP pere_id');
    }
}
