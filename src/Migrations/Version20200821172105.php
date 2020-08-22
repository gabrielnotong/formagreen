<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821172105 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_member (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, starts_at DATETIME DEFAULT NULL, ends_at DATETIME DEFAULT NULL, qr_code LONGTEXT DEFAULT NULL, phone_number VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, discr VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) DEFAULT NULL, introduction VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, INDEX IDX_15B6E145C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE green_space (id INT AUTO_INCREMENT NOT NULL, training_structure_id INT NOT NULL, latitude NUMERIC(8, 6) NOT NULL, longitude NUMERIC(9, 6) NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A72AB67B3D272C86 (training_structure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prestation (id INT AUTO_INCREMENT NOT NULL, user_member_id INT NOT NULL, discount_id INT DEFAULT NULL, green_space_id INT DEFAULT NULL, starts_at DATETIME NOT NULL, ends_at DATETIME NOT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_51C88FAD75775E1A (user_member_id), INDEX IDX_51C88FAD4C7C611F (discount_id), INDEX IDX_51C88FAD57C182CE (green_space_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE center_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_user (role_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_332CA4DDD60322AC (role_id), INDEX IDX_332CA4DDA76ED395 (user_id), PRIMARY KEY(role_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE discount (id INT AUTO_INCREMENT NOT NULL, partner_id INT NOT NULL, percentage NUMERIC(3, 2) NOT NULL, description VARCHAR(255) NOT NULL, starts_at DATETIME NOT NULL, ends_at DATETIME NOT NULL, INDEX IDX_E1E0B40E9393F8FE (partner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE partner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_member ADD CONSTRAINT FK_15B6E145C54C8C93 FOREIGN KEY (type_id) REFERENCES center_type (id)');
        $this->addSql('ALTER TABLE green_space ADD CONSTRAINT FK_A72AB67B3D272C86 FOREIGN KEY (training_structure_id) REFERENCES user_member (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD75775E1A FOREIGN KEY (user_member_id) REFERENCES user_member (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD4C7C611F FOREIGN KEY (discount_id) REFERENCES discount (id)');
        $this->addSql('ALTER TABLE prestation ADD CONSTRAINT FK_51C88FAD57C182CE FOREIGN KEY (green_space_id) REFERENCES green_space (id)');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_user ADD CONSTRAINT FK_332CA4DDA76ED395 FOREIGN KEY (user_id) REFERENCES user_member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE discount ADD CONSTRAINT FK_E1E0B40E9393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE green_space DROP FOREIGN KEY FK_A72AB67B3D272C86');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD75775E1A');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDA76ED395');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD57C182CE');
        $this->addSql('ALTER TABLE user_member DROP FOREIGN KEY FK_15B6E145C54C8C93');
        $this->addSql('ALTER TABLE role_user DROP FOREIGN KEY FK_332CA4DDD60322AC');
        $this->addSql('ALTER TABLE prestation DROP FOREIGN KEY FK_51C88FAD4C7C611F');
        $this->addSql('ALTER TABLE discount DROP FOREIGN KEY FK_E1E0B40E9393F8FE');
        $this->addSql('DROP TABLE user_member');
        $this->addSql('DROP TABLE green_space');
        $this->addSql('DROP TABLE prestation');
        $this->addSql('DROP TABLE center_type');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_user');
        $this->addSql('DROP TABLE discount');
        $this->addSql('DROP TABLE partner');
    }
}
