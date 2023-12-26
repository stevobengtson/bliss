<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217191759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Transaction table, and update Account defaults';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE transaction (id UUID NOT NULL, account_id UUID NOT NULL, entered_date DATE NOT NULL, cleared_date DATE DEFAULT NULL, memo VARCHAR(2048) DEFAULT NULL, credit BIGINT DEFAULT 0 NOT NULL, debit BIGINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D19B6B5FBA ON transaction (account_id)');
        $this->addSql('COMMENT ON COLUMN transaction.id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.account_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account ALTER starting_balance SET DEFAULT 0');
        $this->addSql('ALTER TABLE account ALTER balance SET DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D19B6B5FBA');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('ALTER TABLE account ALTER starting_balance DROP DEFAULT');
        $this->addSql('ALTER TABLE account ALTER balance DROP DEFAULT');
    }
}
