<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230021920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_group (id UUID NOT NULL, name VARCHAR(255) NOT NULL, assigned BIGINT DEFAULT 0 NOT NULL, activity BIGINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category_group.id IS \'(DC2Type:ulid)\'');
        $this->addSql('CREATE TABLE payee (id UUID NOT NULL, auto_category_id UUID DEFAULT NULL, parent_payee_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C218DE5EA327E922 ON payee (auto_category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C218DE5E93791A5E ON payee (parent_payee_id)');
        $this->addSql('COMMENT ON COLUMN payee.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN payee.auto_category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN payee.parent_payee_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE payee ADD CONSTRAINT FK_C218DE5EA327E922 FOREIGN KEY (auto_category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payee ADD CONSTRAINT FK_C218DE5E93791A5E FOREIGN KEY (parent_payee_id) REFERENCES payee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD category_group_id UUID NOT NULL');
        $this->addSql('COMMENT ON COLUMN category.category_group_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1492E5D3C FOREIGN KEY (category_group_id) REFERENCES category_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_64C19C1492E5D3C ON category (category_group_id)');
        $this->addSql('ALTER TABLE transaction ADD category_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN transaction.category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_723705D112469DE2 ON transaction (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C1492E5D3C');
        $this->addSql('ALTER TABLE payee DROP CONSTRAINT FK_C218DE5EA327E922');
        $this->addSql('ALTER TABLE payee DROP CONSTRAINT FK_C218DE5E93791A5E');
        $this->addSql('DROP TABLE category_group');
        $this->addSql('DROP TABLE payee');
        $this->addSql('DROP INDEX IDX_64C19C1492E5D3C');
        $this->addSql('ALTER TABLE category DROP category_group_id');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D112469DE2');
        $this->addSql('DROP INDEX IDX_723705D112469DE2');
        $this->addSql('ALTER TABLE transaction DROP category_id');
    }
}
