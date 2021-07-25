<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725095503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE base_information_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE base_information (id INT NOT NULL, family VARCHAR(255), PRIMARY KEY(id))');
        $this->addSql('DROP TABLE cache_items');
        $this->addSql('ALTER TABLE pokemon ADD base_information_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F39CC5811C FOREIGN KEY (base_information_id) REFERENCES base_information (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62DC90F39CC5811C ON pokemon (base_information_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F39CC5811C');
        $this->addSql('DROP SEQUENCE base_information_id_seq CASCADE');
        $this->addSql('CREATE TABLE cache_items (item_id VARCHAR(255) NOT NULL, item_data BYTEA NOT NULL, item_lifetime INT DEFAULT NULL, item_time INT NOT NULL, PRIMARY KEY(item_id))');
        $this->addSql('DROP TABLE base_information');
        $this->addSql('DROP INDEX UNIQ_62DC90F39CC5811C');
        $this->addSql('ALTER TABLE pokemon DROP base_information_id');
    }
}
