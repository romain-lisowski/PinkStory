<?php

declare(strict_types=1);

namespace App\Migration\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201230221940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_STORY_HAS_STORY_THEME ON sty_story_has_story_theme (story_id, story_theme_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_STORY_IMAGE_HAS_STORY_THEME ON sty_story_image_has_story_theme (story_image_id, story_theme_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_STORY_IMAGE_TRANSLATION ON sty_story_image_translation (story_image_id, language_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_STORY_THEME_TRANSLATION ON sty_story_theme_translation (story_theme_id, language_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_USER_HAS_READING_LANGUAGE ON usr_user_has_reading_language (user_id, language_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_USER_HAS_READING_LANGUAGE');
        $this->addSql('DROP INDEX UNIQ_STORY_IMAGE_HAS_STORY_THEME');
        $this->addSql('DROP INDEX UNIQ_STORY_HAS_STORY_THEME');
        $this->addSql('DROP INDEX UNIQ_STORY_IMAGE_TRANSLATION');
        $this->addSql('DROP INDEX UNIQ_STORY_THEME_TRANSLATION');
    }
}
