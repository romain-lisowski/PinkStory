<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130141341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sty_story ADD language_id UUID NOT NULL');
        $this->addSql('ALTER TABLE sty_story ADD CONSTRAINT FK_AE61A82B82F1BAF4 FOREIGN KEY (language_id) REFERENCES lng_language (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AE61A82B82F1BAF4 ON sty_story (language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sty_story DROP CONSTRAINT FK_AE61A82B82F1BAF4');
        $this->addSql('DROP INDEX IDX_AE61A82B82F1BAF4');
        $this->addSql('ALTER TABLE sty_story DROP language_id');
    }
}
