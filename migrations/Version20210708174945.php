<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210708174945 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_specy ADD generation_id INT');
        $this->addSql('ALTER TABLE pokemon_specy ADD CONSTRAINT FK_D5938CFD553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D5938CFD553A6EC4 ON pokemon_specy (generation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pokemon_specy DROP CONSTRAINT FK_D5938CFD553A6EC4');
        $this->addSql('DROP INDEX IDX_D5938CFD553A6EC4');
        $this->addSql('ALTER TABLE pokemon_specy DROP generation_id');
    }
}
