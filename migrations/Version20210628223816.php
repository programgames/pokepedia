<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210628223816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT NOT NULL, name VARCHAR(255) NOT NULL, gen INT NOT NULL, is_first BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE game_move_extra (id INT NOT NULL, move_id INT NOT NULL, game_id INT NOT NULL, start_at INT DEFAULT NULL, price INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FE1D5216DC541A8 ON game_move_extra (move_id)');
        $this->addSql('CREATE INDEX IDX_6FE1D521E48FD905 ON game_move_extra (game_id)');
        $this->addSql('CREATE TABLE move (id INT NOT NULL, learning_type VARCHAR(255) NOT NULL, english_name VARCHAR(255) NOT NULL, generation INT NOT NULL, forms VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move_pokemon (move_id INT NOT NULL, pokemon_id INT NOT NULL, PRIMARY KEY(move_id, pokemon_id))');
        $this->addSql('CREATE INDEX IDX_901156A46DC541A8 ON move_pokemon (move_id)');
        $this->addSql('CREATE INDEX IDX_901156A42FE71C3E ON move_pokemon (pokemon_id)');
        $this->addSql('CREATE TABLE move_alias (id INT NOT NULL, move_name_id INT NOT NULL, name VARCHAR(255) NOT NULL, gen VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D6E743D83DA5A81 ON move_alias (move_name_id)');
        $this->addSql('CREATE TABLE move_name (id INT NOT NULL, move_identifier INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, pokemon_identifier INT NOT NULL, generation INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pokemon_name (id INT NOT NULL, pokemon_id INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_628A00452FE71C3E ON pokemon_name (pokemon_id)');
        $this->addSql('ALTER TABLE game_move_extra ADD CONSTRAINT FK_6FE1D5216DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_move_extra ADD CONSTRAINT FK_6FE1D521E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE move_pokemon ADD CONSTRAINT FK_901156A46DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE move_pokemon ADD CONSTRAINT FK_901156A42FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE move_alias ADD CONSTRAINT FK_2D6E743D83DA5A81 FOREIGN KEY (move_name_id) REFERENCES move_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_name ADD CONSTRAINT FK_628A00452FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game_move_extra DROP CONSTRAINT FK_6FE1D521E48FD905');
        $this->addSql('ALTER TABLE game_move_extra DROP CONSTRAINT FK_6FE1D5216DC541A8');
        $this->addSql('ALTER TABLE move_pokemon DROP CONSTRAINT FK_901156A46DC541A8');
        $this->addSql('ALTER TABLE move_alias DROP CONSTRAINT FK_2D6E743D83DA5A81');
        $this->addSql('ALTER TABLE move_pokemon DROP CONSTRAINT FK_901156A42FE71C3E');
        $this->addSql('ALTER TABLE pokemon_name DROP CONSTRAINT FK_628A00452FE71C3E');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_move_extra');
        $this->addSql('DROP TABLE move');
        $this->addSql('DROP TABLE move_pokemon');
        $this->addSql('DROP TABLE move_alias');
        $this->addSql('DROP TABLE move_name');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_name');
    }
}
