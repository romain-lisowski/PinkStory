<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130123003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lng_language (id UUID NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, locale VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sty_story_image_translation (id UUID NOT NULL, story_image_id UUID NOT NULL, language_id UUID NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D9AC0F953E403BF1 ON sty_story_image_translation (story_image_id)');
        $this->addSql('CREATE INDEX IDX_D9AC0F9582F1BAF4 ON sty_story_image_translation (language_id)');
        $this->addSql('ALTER TABLE sty_story_image_translation ADD CONSTRAINT FK_D9AC0F953E403BF1 FOREIGN KEY (story_image_id) REFERENCES sty_story_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_image_translation ADD CONSTRAINT FK_D9AC0F9582F1BAF4 FOREIGN KEY (language_id) REFERENCES lng_language (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_image ADD reference VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE sty_story_image SET reference = \'\'');
        $this->addSql('ALTER TABLE sty_story_image ALTER COLUMN reference SET NOT NULL');
        $this->addSql('ALTER TABLE sty_story_image DROP title');
        $this->addSql('ALTER TABLE sty_story_image DROP title_slug');
        $this->addSql('ALTER TABLE usr_user ALTER image_defined DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sty_story_image_translation DROP CONSTRAINT FK_D9AC0F9582F1BAF4');
        $this->addSql('DROP TABLE lng_language');
        $this->addSql('DROP TABLE sty_story_image_translation');
        $this->addSql('ALTER TABLE sty_story_image ADD title_slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sty_story_image RENAME COLUMN reference TO title');
        $this->addSql('ALTER TABLE usr_user ALTER image_defined SET DEFAULT \'false\'');
    }
}
