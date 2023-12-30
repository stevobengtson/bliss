<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230023346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_group ADD owner_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN category_group.owner_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE category_group ADD CONSTRAINT FK_85F30B8C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_85F30B8C7E3C61F9 ON category_group (owner_id)');
        $this->addSql('ALTER TABLE payee ADD owner_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN payee.owner_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE payee ADD CONSTRAINT FK_C218DE5E7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C218DE5E7E3C61F9 ON payee (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE payee DROP CONSTRAINT FK_C218DE5E7E3C61F9');
        $this->addSql('DROP INDEX IDX_C218DE5E7E3C61F9');
        $this->addSql('ALTER TABLE payee DROP owner_id');
        $this->addSql('ALTER TABLE category_group DROP CONSTRAINT FK_85F30B8C7E3C61F9');
        $this->addSql('DROP INDEX IDX_85F30B8C7E3C61F9');
        $this->addSql('ALTER TABLE category_group DROP owner_id');
    }
}
