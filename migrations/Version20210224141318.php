<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210224141318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job_info (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(3000) NOT NULL, UNIQUE INDEX UNIQ_4C454716CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(1000) NOT NULL, type VARCHAR(255) NOT NULL, priority INT NOT NULL, is_archived TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_info ADD CONSTRAINT FK_4C454716CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE contract_base64 CHANGE base64 base64 MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE work_contract CHANGE base64 base64 MEDIUMTEXT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE job_info');
        $this->addSql('DROP TABLE notification');
        $this->addSql('ALTER TABLE contract_base64 CHANGE base64 base64 MEDIUMTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE work_contract CHANGE base64 base64 MEDIUMTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
