<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240122155532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_group (id UUID NOT NULL, budget_id UUID NOT NULL, owner_id UUID NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_85F30B8C36ABA6B8 ON category_group (budget_id)');
        $this->addSql('CREATE INDEX IDX_85F30B8C7E3C61F9 ON category_group (owner_id)');
        $this->addSql('COMMENT ON COLUMN category_group.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN category_group.budget_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN category_group.owner_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN category_group.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN category_group.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE category_group ADD CONSTRAINT FK_85F30B8C36ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category_group ADD CONSTRAINT FK_85F30B8C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category_group DROP CONSTRAINT FK_85F30B8C36ABA6B8');
        $this->addSql('ALTER TABLE category_group DROP CONSTRAINT FK_85F30B8C7E3C61F9');
        $this->addSql('DROP TABLE category_group');
    }
}
