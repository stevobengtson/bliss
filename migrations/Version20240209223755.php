<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209223755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payee DROP CONSTRAINT fk_c218de5edfc611b1');
        $this->addSql('DROP INDEX idx_c218de5edfc611b1');
        $this->addSql('ALTER TABLE payee DROP link_category_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payee ADD link_category_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN payee.link_category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE payee ADD CONSTRAINT fk_c218de5edfc611b1 FOREIGN KEY (link_category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c218de5edfc611b1 ON payee (link_category_id)');
    }
}
