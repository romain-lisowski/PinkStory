<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821144105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usr_user ADD password_forgotten_secret UUID NOT NULL');
        $this->addSql('ALTER TABLE usr_user ADD password_forgotten_secret_used BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE usr_user ADD password_forgotten_secret_created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6C77AE3B3FF2FF ON usr_user (password_forgotten_secret)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C6C77AE3B3FF2FF');
        $this->addSql('ALTER TABLE usr_user DROP password_forgotten_secret');
        $this->addSql('ALTER TABLE usr_user DROP password_forgotten_secret_used');
        $this->addSql('ALTER TABLE usr_user DROP password_forgotten_secret_created_at');
    }
}
