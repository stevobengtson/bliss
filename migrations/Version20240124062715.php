<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124062715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payee (id UUID NOT NULL, budget_id UUID NOT NULL, owner_id UUID NOT NULL, link_category_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C218DE5E36ABA6B8 ON payee (budget_id)');
        $this->addSql('CREATE INDEX IDX_C218DE5E7E3C61F9 ON payee (owner_id)');
        $this->addSql('CREATE INDEX IDX_C218DE5EDFC611B1 ON payee (link_category_id)');
        $this->addSql('COMMENT ON COLUMN payee.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN payee.budget_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN payee.owner_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN payee.link_category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE payee ADD CONSTRAINT FK_C218DE5E36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payee ADD CONSTRAINT FK_C218DE5E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payee ADD CONSTRAINT FK_C218DE5EDFC611B1 FOREIGN KEY (link_category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payee DROP CONSTRAINT FK_C218DE5E36ABA6B8');
        $this->addSql('ALTER TABLE payee DROP CONSTRAINT FK_C218DE5E7E3C61F9');
        $this->addSql('ALTER TABLE payee DROP CONSTRAINT FK_C218DE5EDFC611B1');
        $this->addSql('DROP TABLE payee');
    }
}
