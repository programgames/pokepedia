<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627212740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE move (id INT NOT NULL, learning_type VARCHAR(255) NOT NULL, english_name VARCHAR(255) NOT NULL, generation INT NOT NULL, games VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move_pokemon (move_id INT NOT NULL, pokemon_id INT NOT NULL, PRIMARY KEY(move_id, pokemon_id))');
        $this->addSql('CREATE INDEX IDX_901156A46DC541A8 ON move_pokemon (move_id)');
        $this->addSql('CREATE INDEX IDX_901156A42FE71C3E ON move_pokemon (pokemon_id)');
        $this->addSql('CREATE TABLE move_name (id INT NOT NULL, move_id INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, english_name VARCHAR(255) NOT NULL, pokemon_id INT NOT NULL, generation INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon_name (id INT NOT NULL, species_id INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX trad ON pokemon_name (species_id, language_id)');
        $this->addSql('ALTER TABLE move_pokemon ADD CONSTRAINT FK_901156A46DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE move_pokemon ADD CONSTRAINT FK_901156A42FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE move_pokemon DROP CONSTRAINT FK_901156A46DC541A8');
        $this->addSql('ALTER TABLE move_pokemon DROP CONSTRAINT FK_901156A42FE71C3E');
        $this->addSql('DROP TABLE move');
        $this->addSql('DROP TABLE move_pokemon');
        $this->addSql('DROP TABLE move_name');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_name');
    }
}
