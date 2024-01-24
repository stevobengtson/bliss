<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124070807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD budget_id UUID NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD category_id UUID NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD payee_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN transaction.budget_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.category_id IS \'(DC2Type:ulid)\'');
        $this->addSql('COMMENT ON COLUMN transaction.payee_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D136ABA6B8 FOREIGN KEY (budget_id) REFERENCES budget (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1CB4B68F FOREIGN KEY (payee_id) REFERENCES payee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_723705D136ABA6B8 ON transaction (budget_id)');
        $this->addSql('CREATE INDEX IDX_723705D112469DE2 ON transaction (category_id)');
        $this->addSql('CREATE INDEX IDX_723705D1CB4B68F ON transaction (payee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D136ABA6B8');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D112469DE2');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1CB4B68F');
        $this->addSql('DROP INDEX IDX_723705D136ABA6B8');
        $this->addSql('DROP INDEX IDX_723705D112469DE2');
        $this->addSql('DROP INDEX IDX_723705D1CB4B68F');
        $this->addSql('ALTER TABLE transaction DROP budget_id');
        $this->addSql('ALTER TABLE transaction DROP category_id');
        $this->addSql('ALTER TABLE transaction DROP payee_id');
    }
}
