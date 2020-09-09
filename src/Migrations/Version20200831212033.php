<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200831212033 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'CREATES User admin';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->connection->exec('INSERT INTO user_member(
                center_type_id,
                email,
                hash,
                starts_at,
                ends_at,
                qr_code,
                phone_number,
                status,
                `type` ,
                first_name,
                last_name,
                slug,
                company_name,
                street_name,
                country,
                city,
                street_number,
                zip_code,
                deleted
                ) VALUES (
                null,
                "gabs@gmail.com",
                "$2y$13$dGtSWlv2uaJM0g6IQf2SBuJ288camhqNCmrXAiyY05bP1VheR8UrO",
                null,
                null,
                null,
                null,
                1,
                "",
                "Gabriel",
                "Notong",
                "gabriel-notong",
                null,
                null,
                null,
                null,
                null,
                null,
                0
                )
        ');

        $userId = $this->connection->lastInsertId();

        $roles = $this->connection->fetchAll('SELECT id FROM role WHERE name="ROLE_ADMIN"');
        $roleId = $roles[0]['id'];

        $this->connection->exec("INSERT INTO role_user(role_id, user_id) VALUES (${roleId}, ${userId})");
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }
}
