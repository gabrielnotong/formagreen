<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200818115439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE training_structure (id INT AUTO_INCREMENT NOT NULL, type_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, INDEX IDX_18D541EC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE green_space (id INT AUTO_INCREMENT NOT NULL, latitude NUMERIC(8, 6) NOT NULL, longitude NUMERIC(9, 6) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE structure_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, user_member_id INT NOT NULL, discount_id INT DEFAULT NULL, green_space_id INT DEFAULT NULL, starts_at DATETIME NOT NULL, ends_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_51C88FAD75775E1A (user_member_id), INDEX IDX_51C88FAD4C7C611F (discount_id), INDEX IDX_51C88FAD57C182CE (green_space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, partner_id INT NOT NULL, percentage NUMERIC(3, 2) NOT NULL, INDEX IDX_E1E0B40E9393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training_structure ADD CONSTRAINT FK_18D541EC54C8C93 FOREIGN KEY (type_id) REFERENCES structure_type (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD75775E1A FOREIGN KEY (user_member_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD4C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD57C182CE FOREIGN KEY (green_space_id) REFERENCES green_space (id)');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('ALTER TABLE user ADD starts_at DATETIME NOT NULL, ADD ends_at DATETIME NOT NULL, ADD qr_code LONGTEXT DEFAULT NULL, ADD phone_number VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD57C182CE');
        $this->addSql('ALTER TABLE training_structure DROP FOREIGN KEY FK_18D541EC54C8C93');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD4C7C611F');
        $this->addSql('ALTER TABLE discount DROP FOREIGN KEY FK_E1E0B40E9393F8FE');
        $this->addSql('DROP TABLE training_structure');
        $this->addSql('DROP TABLE green_space');
        $this->addSql('DROP TABLE structure_type');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE partner');
        $this->addSql('ALTER TABLE user DROP starts_at, DROP ends_at, DROP qr_code, DROP phone_number');
    }
}
