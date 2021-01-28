<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201221160930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token DROP INDEX IDX_7BA2F5EBA76ED395, ADD UNIQUE INDEX UNIQ_7BA2F5EBA76ED395 (user_id)');
        $this->addSql('ALTER TABLE contract ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E98F2859A76ED395 ON contract (user_id)');
        $this->addSql('ALTER TABLE user ADD is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token DROP INDEX UNIQ_7BA2F5EBA76ED395, ADD INDEX IDX_7BA2F5EBA76ED395 (user_id)');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859A76ED395');
        $this->addSql('DROP INDEX UNIQ_E98F2859A76ED395 ON contract');
        $this->addSql('ALTER TABLE contract DROP user_id');
        $this->addSql('ALTER TABLE user DROP is_active');
    }
}
