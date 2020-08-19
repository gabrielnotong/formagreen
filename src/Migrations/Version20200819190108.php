<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819190108 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE green_space ADD training_structure_id INT NOT NULL');
        $this->addSql('ALTER TABLE green_space ADD CONSTRAINT FK_A72AB67B3D272C86 FOREIGN KEY (training_structure_id) REFERENCES training_structure (id)');
        $this->addSql('CREATE INDEX IDX_A72AB67B3D272C86 ON green_space (training_structure_id)');
        $this->addSql('ALTER TABLE discount ADD description VARCHAR(255) NOT NULL, ADD starts_at DATETIME NOT NULL, ADD ends_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE discount DROP description, DROP starts_at, DROP ends_at');
        $this->addSql('ALTER TABLE green_space DROP FOREIGN KEY FK_A72AB67B3D272C86');
        $this->addSql('DROP INDEX IDX_A72AB67B3D272C86 ON green_space');
        $this->addSql('ALTER TABLE green_space DROP training_structure_id');
    }
}
