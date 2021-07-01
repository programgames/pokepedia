<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210701160305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT NOT NULL, name VARCHAR(255) NOT NULL, gen INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_game ON game (name)');
        $this->addSql('CREATE TABLE leveling_up_move (id INT NOT NULL, pokemon_id INT NOT NULL, move VARCHAR(255) NOT NULL, level INT DEFAULT NULL, type VARCHAR(255) NOT NULL, attack_type VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, power INT DEFAULT NULL, accuracy INT DEFAULT NULL, power_points INT NOT NULL, form VARCHAR(255) DEFAULT NULL, generation INT NOT NULL, red BOOLEAN DEFAULT NULL, blue BOOLEAN DEFAULT NULL, green BOOLEAN DEFAULT NULL, yellow BOOLEAN DEFAULT NULL, silver BOOLEAN DEFAULT NULL, gold BOOLEAN DEFAULT NULL, crystal BOOLEAN DEFAULT NULL, fire_red BOOLEAN DEFAULT NULL, leaf_green BOOLEAN DEFAULT NULL, emerald BOOLEAN DEFAULT NULL, diamond BOOLEAN DEFAULT NULL, pearl BOOLEAN DEFAULT NULL, platinum BOOLEAN DEFAULT NULL, heart_gold BOOLEAN DEFAULT NULL, soul_silver BOOLEAN DEFAULT NULL, black BOOLEAN DEFAULT NULL, white BOOLEAN DEFAULT NULL, black2 BOOLEAN DEFAULT NULL, white2 BOOLEAN DEFAULT NULL, x BOOLEAN DEFAULT NULL, y BOOLEAN DEFAULT NULL, omega_ruby BOOLEAN DEFAULT NULL, omega_sapphire BOOLEAN DEFAULT NULL, sun BOOLEAN DEFAULT NULL, moon BOOLEAN DEFAULT NULL, ultra_moon BOOLEAN DEFAULT NULL, ultra_sun BOOLEAN DEFAULT NULL, sword BOOLEAN DEFAULT NULL, shield BOOLEAN DEFAULT NULL, constest VARCHAR(255) DEFAULT NULL, appeal INT DEFAULT NULL, jam INT DEFAULT NULL, ruby BOOLEAN DEFAULT NULL, sapphire BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BEB08D0F2FE71C3E ON leveling_up_move (pokemon_id)');
        $this->addSql('CREATE TABLE move_alias (id INT NOT NULL, move_name_id INT NOT NULL, name VARCHAR(255) NOT NULL, gen VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D6E743D83DA5A81 ON move_alias (move_name_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_alias ON move_alias (name, move_name_id)');
        $this->addSql('CREATE TABLE move_name (id INT NOT NULL, move_identifier INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_move_name ON move_name (move_identifier, language_id, name)');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, pokemon_identifier INT NOT NULL, generation INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_pokemon ON pokemon (pokemon_identifier)');
        $this->addSql('CREATE TABLE pokemon_name (id INT NOT NULL, pokemon_id INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_628A00452FE71C3E ON pokemon_name (pokemon_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_pokemon_hame ON pokemon_name (language_id, name)');
        $this->addSql('CREATE TABLE tutoring_move (id INT NOT NULL, pokemon_id INT NOT NULL, move VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, attack_type VARCHAR(255) NOT NULL, category VARCHAR(255) DEFAULT NULL, power INT DEFAULT NULL, accuracy INT DEFAULT NULL, power_points INT NOT NULL, form VARCHAR(255) DEFAULT NULL, generation INT NOT NULL, red BOOLEAN DEFAULT NULL, blue BOOLEAN DEFAULT NULL, green BOOLEAN DEFAULT NULL, yellow BOOLEAN DEFAULT NULL, silver BOOLEAN DEFAULT NULL, gold BOOLEAN DEFAULT NULL, crystal BOOLEAN DEFAULT NULL, fire_red BOOLEAN DEFAULT NULL, leaf_green BOOLEAN DEFAULT NULL, emerald BOOLEAN DEFAULT NULL, diamond BOOLEAN DEFAULT NULL, pearl BOOLEAN DEFAULT NULL, platinum BOOLEAN DEFAULT NULL, heart_gold BOOLEAN DEFAULT NULL, soul_silver BOOLEAN DEFAULT NULL, black BOOLEAN DEFAULT NULL, white BOOLEAN DEFAULT NULL, black2 BOOLEAN DEFAULT NULL, white2 BOOLEAN DEFAULT NULL, x BOOLEAN DEFAULT NULL, y BOOLEAN DEFAULT NULL, omega_ruby BOOLEAN DEFAULT NULL, omega_sapphire BOOLEAN DEFAULT NULL, sun BOOLEAN DEFAULT NULL, moon BOOLEAN DEFAULT NULL, ultra_moon BOOLEAN DEFAULT NULL, ultra_sun BOOLEAN DEFAULT NULL, sword BOOLEAN DEFAULT NULL, shield BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C77301F2FE71C3E ON tutoring_move (pokemon_id)');
        $this->addSql('ALTER TABLE leveling_up_move ADD CONSTRAINT FK_BEB08D0F2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE move_alias ADD CONSTRAINT FK_2D6E743D83DA5A81 FOREIGN KEY (move_name_id) REFERENCES move_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_name ADD CONSTRAINT FK_628A00452FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tutoring_move ADD CONSTRAINT FK_C77301F2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE move_alias DROP CONSTRAINT FK_2D6E743D83DA5A81');
        $this->addSql('ALTER TABLE leveling_up_move DROP CONSTRAINT FK_BEB08D0F2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_name DROP CONSTRAINT FK_628A00452FE71C3E');
        $this->addSql('ALTER TABLE tutoring_move DROP CONSTRAINT FK_C77301F2FE71C3E');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE leveling_up_move');
        $this->addSql('DROP TABLE move_alias');
        $this->addSql('DROP TABLE move_name');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_name');
        $this->addSql('DROP TABLE tutoring_move');
    }
}
