<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210709185744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE move_name ADD gen1 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_name ADD gen2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_name ADD gen3 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_name ADD gen4 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_name ADD gen5 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_name ADD gen6 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_name ADD gen7 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_name ADD gen8 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_specy ALTER generation_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE move_name DROP gen1');
        $this->addSql('ALTER TABLE move_name DROP gen2');
        $this->addSql('ALTER TABLE move_name DROP gen3');
        $this->addSql('ALTER TABLE move_name DROP gen4');
        $this->addSql('ALTER TABLE move_name DROP gen5');
        $this->addSql('ALTER TABLE move_name DROP gen6');
        $this->addSql('ALTER TABLE move_name DROP gen7');
        $this->addSql('ALTER TABLE move_name DROP gen8');
        $this->addSql('ALTER TABLE pokemon_specy ALTER generation_id DROP NOT NULL');
    }
}
