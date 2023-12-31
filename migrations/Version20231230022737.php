<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230022737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD payee_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN transaction.payee_id IS \'(DC2Type:ulid)\'');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1CB4B68F FOREIGN KEY (payee_id) REFERENCES payee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_723705D1CB4B68F ON transaction (payee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1CB4B68F');
        $this->addSql('DROP INDEX IDX_723705D1CB4B68F');
        $this->addSql('ALTER TABLE transaction DROP payee_id');
    }
}
