<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201165101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_c6c77ae8dffa5f9');
        $this->addSql('ALTER TABLE usr_user ADD email_validation_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE usr_user DROP email_validation_secret');
        $this->addSql('ALTER TABLE usr_user RENAME COLUMN email_validation_secret_used TO email_validation_code_used');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usr_user ADD email_validation_secret UUID NOT NULL');
        $this->addSql('ALTER TABLE usr_user DROP email_validation_code');
        $this->addSql('ALTER TABLE usr_user RENAME COLUMN email_validation_code_used TO email_validation_secret_used');
        $this->addSql('CREATE UNIQUE INDEX uniq_c6c77ae8dffa5f9 ON usr_user (email_validation_secret)');
    }
}
