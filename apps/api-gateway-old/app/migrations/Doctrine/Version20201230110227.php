<?php

declare(strict_types=1);

namespace App\Migration\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230110227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usr_user_has_reading_language (id UUID NOT NULL, user_id UUID NOT NULL, language_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B2C77264A76ED395 ON usr_user_has_reading_language (user_id)');
        $this->addSql('CREATE INDEX IDX_B2C7726482F1BAF4 ON usr_user_has_reading_language (language_id)');
        $this->addSql('ALTER TABLE usr_user_has_reading_language ADD CONSTRAINT FK_B2C77264A76ED395 FOREIGN KEY (user_id) REFERENCES usr_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE usr_user_has_reading_language ADD CONSTRAINT FK_B2C7726482F1BAF4 FOREIGN KEY (language_id) REFERENCES lng_language (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE usr_user_has_reading_language');
    }
}
