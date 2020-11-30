<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130134925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sty_story_theme_translation (id UUID NOT NULL, story_theme_id UUID NOT NULL, language_id UUID NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FE5157145AE76A1B ON sty_story_theme_translation (story_theme_id)');
        $this->addSql('CREATE INDEX IDX_FE51571482F1BAF4 ON sty_story_theme_translation (language_id)');
        $this->addSql('ALTER TABLE sty_story_theme_translation ADD CONSTRAINT FK_FE5157145AE76A1B FOREIGN KEY (story_theme_id) REFERENCES sty_story_theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_theme_translation ADD CONSTRAINT FK_FE51571482F1BAF4 FOREIGN KEY (language_id) REFERENCES lng_language (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_theme ADD reference VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE sty_story_theme SET reference = \'\'');
        $this->addSql('ALTER TABLE sty_story_theme ALTER COLUMN reference SET NOT NULL');
        $this->addSql('ALTER TABLE sty_story_theme DROP title');
        $this->addSql('ALTER TABLE sty_story_theme DROP title_slug');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sty_story_theme_translation');
        $this->addSql('ALTER TABLE sty_story_theme ADD title_slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sty_story_theme RENAME COLUMN reference TO title');
    }
}
