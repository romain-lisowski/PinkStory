<?php

declare(strict_types=1);

namespace App\Migration\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201218092756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lng_language DROP activated');
        $this->addSql('ALTER TABLE sty_story DROP activated');
        $this->addSql('ALTER TABLE sty_story_has_story_theme DROP activated');
        $this->addSql('ALTER TABLE sty_story_image DROP activated');
        $this->addSql('ALTER TABLE sty_story_image_has_story_theme DROP activated');
        $this->addSql('ALTER TABLE sty_story_image_translation DROP activated');
        $this->addSql('ALTER TABLE sty_story_rating DROP activated');
        $this->addSql('ALTER TABLE sty_story_theme DROP activated');
        $this->addSql('ALTER TABLE sty_story_theme_translation DROP activated');
        $this->addSql('ALTER TABLE usr_user DROP activated');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sty_story_theme ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE sty_story_image_has_story_theme ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE sty_story_has_story_theme ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE sty_story ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE usr_user ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE sty_story_image_translation ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE lng_language ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE sty_story_image ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE sty_story_theme_translation ADD activated BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE sty_story_rating ADD activated BOOLEAN NOT NULL');
    }
}
