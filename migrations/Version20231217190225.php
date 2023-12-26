<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231217190225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Cleared and Uncleared columns to Account table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE account ADD cleared_balance BIGINT NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE account ADD uncleared_balance BIGINT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE account DROP cleared_balance');
        $this->addSql('ALTER TABLE account DROP uncleared_balance');
    }
}
