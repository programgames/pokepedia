<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730163144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pokemon_name_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pokemon_name (id INT NOT NULL, bulbapedia_name VARCHAR(255) NOT NULL, pokepedia_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE cache_items');
        $this->addSql('ALTER TABLE pokemon ADD pokemon_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F31D7DE047 FOREIGN KEY (pokemon_name_id) REFERENCES pokemon_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62DC90F31D7DE047 ON pokemon (pokemon_name_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F31D7DE047');
        $this->addSql('DROP SEQUENCE pokemon_name_id_seq CASCADE');
        $this->addSql('CREATE TABLE cache_items (item_id VARCHAR(255) NOT NULL, item_data BYTEA NOT NULL, item_lifetime INT DEFAULT NULL, item_time INT NOT NULL, PRIMARY KEY(item_id))');
        $this->addSql('DROP TABLE pokemon_name');
        $this->addSql('DROP INDEX UNIQ_62DC90F31D7DE047');
        $this->addSql('ALTER TABLE pokemon DROP pokemon_name_id');
    }
}
