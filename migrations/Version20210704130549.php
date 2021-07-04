<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210704130549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE generation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE version_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE generation (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE move_learn_method (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE version_group (id INT NOT NULL, generation_id INT NOT NULL, name VARCHAR(255) NOT NULL, version_group_order INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A1C90C0553A6EC4 ON version_group (generation_id)');
        $this->addSql('ALTER TABLE version_group ADD CONSTRAINT FK_6A1C90C0553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE version_group DROP CONSTRAINT FK_6A1C90C0553A6EC4');
        $this->addSql('DROP SEQUENCE generation_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE version_group_id_seq CASCADE');
        $this->addSql('DROP TABLE generation');
        $this->addSql('DROP TABLE move_learn_method');
        $this->addSql('DROP TABLE version_group');
    }
}
