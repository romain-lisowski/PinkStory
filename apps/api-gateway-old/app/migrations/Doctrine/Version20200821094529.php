<?php

declare(strict_types=1);

namespace App\Migration\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821094529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usr_user ADD email_validated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE usr_user ADD email_validation_secret UUID NOT NULL');
        $this->addSql('ALTER TABLE usr_user ADD email_validation_secret_used BOOLEAN NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C6C77AE8DFFA5F9 ON usr_user (email_validation_secret)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_C6C77AE8DFFA5F9');
        $this->addSql('ALTER TABLE usr_user DROP email_validated');
        $this->addSql('ALTER TABLE usr_user DROP email_validation_secret');
        $this->addSql('ALTER TABLE usr_user DROP email_validation_secret_used');
    }
}
