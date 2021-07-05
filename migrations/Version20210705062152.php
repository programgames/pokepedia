<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705062152 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE move_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE move_name_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE egg_group (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE evolution_chain (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE generation (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move_learn_method (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move_name (id INT NOT NULL, move_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, language INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_504B42B26DC541A8 ON move_name (move_id)');
        $this->addSql('CREATE TABLE pokemon (id INT NOT NULL, pokemon_specy_id INT NOT NULL, name VARCHAR(255) NOT NULL, pokemon_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_62DC90F3256D30DF ON pokemon (pokemon_specy_id)');
        $this->addSql('CREATE TABLE pokemon_specy (id INT NOT NULL, evolution_chain_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, pokemon_species_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D5938CFDE417AC09 ON pokemon_specy (evolution_chain_id)');
        $this->addSql('CREATE TABLE pokemon_specy_egg_group (pokemon_specy_id INT NOT NULL, egg_group_id INT NOT NULL, PRIMARY KEY(pokemon_specy_id, egg_group_id))');
        $this->addSql('CREATE INDEX IDX_4243C8E9256D30DF ON pokemon_specy_egg_group (pokemon_specy_id)');
        $this->addSql('CREATE INDEX IDX_4243C8E9B76DC94C ON pokemon_specy_egg_group (egg_group_id)');
        $this->addSql('CREATE TABLE version_group (id INT NOT NULL, generation_id INT NOT NULL, name VARCHAR(255) NOT NULL, version_group_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A1C90C0553A6EC4 ON version_group (generation_id)');
        $this->addSql('ALTER TABLE move_name ADD CONSTRAINT FK_504B42B26DC541A8 FOREIGN KEY (move_id) REFERENCES move (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3256D30DF FOREIGN KEY (pokemon_specy_id) REFERENCES pokemon_specy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_specy ADD CONSTRAINT FK_D5938CFDE417AC09 FOREIGN KEY (evolution_chain_id) REFERENCES evolution_chain (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group ADD CONSTRAINT FK_4243C8E9256D30DF FOREIGN KEY (pokemon_specy_id) REFERENCES pokemon_specy (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group ADD CONSTRAINT FK_4243C8E9B76DC94C FOREIGN KEY (egg_group_id) REFERENCES egg_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE version_group ADD CONSTRAINT FK_6A1C90C0553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group DROP CONSTRAINT FK_4243C8E9B76DC94C');
        $this->addSql('ALTER TABLE pokemon_specy DROP CONSTRAINT FK_D5938CFDE417AC09');
        $this->addSql('ALTER TABLE version_group DROP CONSTRAINT FK_6A1C90C0553A6EC4');
        $this->addSql('ALTER TABLE move_name DROP CONSTRAINT FK_504B42B26DC541A8');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F3256D30DF');
        $this->addSql('ALTER TABLE pokemon_specy_egg_group DROP CONSTRAINT FK_4243C8E9256D30DF');
        $this->addSql('DROP SEQUENCE move_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE move_name_id_seq CASCADE');
        $this->addSql('DROP TABLE egg_group');
        $this->addSql('DROP TABLE evolution_chain');
        $this->addSql('DROP TABLE generation');
        $this->addSql('DROP TABLE move');
        $this->addSql('DROP TABLE move_learn_method');
        $this->addSql('DROP TABLE move_name');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_specy');
        $this->addSql('DROP TABLE pokemon_specy_egg_group');
        $this->addSql('DROP TABLE version_group');
    }
}
