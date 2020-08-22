<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200822132205 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_member DROP FOREIGN KEY FK_15B6E145C54C8C93');
        $this->addSql('DROP INDEX IDX_15B6E145C54C8C93 ON user_member');
        $this->addSql('ALTER TABLE user_member CHANGE type_id center_type_id INT DEFAULT NULL, CHANGE discr type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_member ADD CONSTRAINT FK_15B6E14580FEB2FF FOREIGN KEY (center_type_id) REFERENCES center_type (id)');
        $this->addSql('CREATE INDEX IDX_15B6E14580FEB2FF ON user_member (center_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_member DROP FOREIGN KEY FK_15B6E14580FEB2FF');
        $this->addSql('DROP INDEX IDX_15B6E14580FEB2FF ON user_member');
        $this->addSql('ALTER TABLE user_member CHANGE center_type_id type_id INT DEFAULT NULL, CHANGE type discr VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_member ADD CONSTRAINT FK_15B6E145C54C8C93 FOREIGN KEY (type_id) REFERENCES center_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_15B6E145C54C8C93 ON user_member (type_id)');
    }
}
