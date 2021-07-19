<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210718150956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE pokemon_availability_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE pokemon_availability (id INT NOT NULL, pokemon_id INT NOT NULL, version_group_id INT NOT NULL, availability BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7E30D9662FE71C3E ON pokemon_availability (pokemon_id)');
        $this->addSql('CREATE INDEX IDX_7E30D96692AE854F ON pokemon_availability (version_group_id)');
        $this->addSql('ALTER TABLE pokemon_availability ADD CONSTRAINT FK_7E30D9662FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_availability ADD CONSTRAINT FK_7E30D96692AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE pokemon_availability_id_seq CASCADE');
        $this->addSql('DROP TABLE pokemon_availability');
    }
}
