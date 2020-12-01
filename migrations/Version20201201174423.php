<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201174423 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, social_reason VARCHAR(64) NOT NULL, siret_number VARCHAR(64) NOT NULL, activity VARCHAR(64) NOT NULL, location_street VARCHAR(64) NOT NULL, location_city VARCHAR(64) NOT NULL, postal_code VARCHAR(10) NOT NULL, phone_number VARCHAR(16) NOT NULL, contract_email VARCHAR(64) DEFAULT NULL, representative_civility TINYINT(1) NOT NULL, representative_first_name VARCHAR(64) NOT NULL, representative_last_name VARCHAR(64) NOT NULL, representative_role VARCHAR(64) NOT NULL, representative_email VARCHAR(64) NOT NULL, other_social_reason VARCHAR(64) DEFAULT NULL, other_location_street VARCHAR(64) DEFAULT NULL, other_location_city VARCHAR(64) DEFAULT NULL, other_postal_code VARCHAR(10) DEFAULT NULL, other_phone_number VARCHAR(16) DEFAULT NULL, worker_role VARCHAR(64) NOT NULL, contract_type VARCHAR(64) NOT NULL, contract_start_date DATETIME NOT NULL, contract_end_date DATETIME NOT NULL, tutor_civility TINYINT(1) NOT NULL, tutor_first_name VARCHAR(64) NOT NULL, tutor_last_name VARCHAR(64) NOT NULL, tutor_role VARCHAR(64) NOT NULL, tutor_phone_number VARCHAR(16) NOT NULL, tutor_email VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP INDEX UNIQ_8D93D649AA08CB10 ON user');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(180) NOT NULL, ADD last_name VARCHAR(180) NOT NULL, CHANGE login email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD login VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP email, DROP first_name, DROP last_name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AA08CB10 ON user (login)');
    }
}
