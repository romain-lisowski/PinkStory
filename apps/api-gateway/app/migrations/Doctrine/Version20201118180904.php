<?php

declare(strict_types=1);

namespace App\Migration\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201118180904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sty_story_theme (id UUID NOT NULL, parent_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, position INT NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_600DCA3A727ACA70 ON sty_story_theme (parent_id)');
        $this->addSql('CREATE TABLE sty_story_image (id UUID NOT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sty_story_image_has_story_theme (id UUID NOT NULL, story_image_id UUID NOT NULL, story_theme_id UUID NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_338D07973E403BF1 ON sty_story_image_has_story_theme (story_image_id)');
        $this->addSql('CREATE INDEX IDX_338D07975AE76A1B ON sty_story_image_has_story_theme (story_theme_id)');
        $this->addSql('CREATE TABLE sty_story (id UUID NOT NULL, user_id UUID NOT NULL, parent_id UUID DEFAULT NULL, story_image_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, content TEXT NOT NULL, position INT NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AE61A82BA76ED395 ON sty_story (user_id)');
        $this->addSql('CREATE INDEX IDX_AE61A82B727ACA70 ON sty_story (parent_id)');
        $this->addSql('CREATE INDEX IDX_AE61A82B3E403BF1 ON sty_story (story_image_id)');
        $this->addSql('CREATE TABLE sty_story_has_story_theme (id UUID NOT NULL, story_id UUID NOT NULL, story_theme_id UUID NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3017E2FAA5D4036 ON sty_story_has_story_theme (story_id)');
        $this->addSql('CREATE INDEX IDX_E3017E2F5AE76A1B ON sty_story_has_story_theme (story_theme_id)');
        $this->addSql('ALTER TABLE sty_story_theme ADD CONSTRAINT FK_600DCA3A727ACA70 FOREIGN KEY (parent_id) REFERENCES sty_story_theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_image_has_story_theme ADD CONSTRAINT FK_338D07973E403BF1 FOREIGN KEY (story_image_id) REFERENCES sty_story_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_image_has_story_theme ADD CONSTRAINT FK_338D07975AE76A1B FOREIGN KEY (story_theme_id) REFERENCES sty_story_theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story ADD CONSTRAINT FK_AE61A82BA76ED395 FOREIGN KEY (user_id) REFERENCES usr_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story ADD CONSTRAINT FK_AE61A82B727ACA70 FOREIGN KEY (parent_id) REFERENCES sty_story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story ADD CONSTRAINT FK_AE61A82B3E403BF1 FOREIGN KEY (story_image_id) REFERENCES sty_story_image (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_has_story_theme ADD CONSTRAINT FK_E3017E2FAA5D4036 FOREIGN KEY (story_id) REFERENCES sty_story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_has_story_theme ADD CONSTRAINT FK_E3017E2F5AE76A1B FOREIGN KEY (story_theme_id) REFERENCES sty_story_theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sty_story DROP CONSTRAINT FK_AE61A82B727ACA70');
        $this->addSql('ALTER TABLE sty_story_has_story_theme DROP CONSTRAINT FK_E3017E2FAA5D4036');
        $this->addSql('ALTER TABLE sty_story DROP CONSTRAINT FK_AE61A82B3E403BF1');
        $this->addSql('ALTER TABLE sty_story_image_has_story_theme DROP CONSTRAINT FK_338D07973E403BF1');
        $this->addSql('ALTER TABLE sty_story_has_story_theme DROP CONSTRAINT FK_E3017E2F5AE76A1B');
        $this->addSql('ALTER TABLE sty_story_image_has_story_theme DROP CONSTRAINT FK_338D07975AE76A1B');
        $this->addSql('ALTER TABLE sty_story_theme DROP CONSTRAINT FK_600DCA3A727ACA70');
        $this->addSql('DROP TABLE sty_story');
        $this->addSql('DROP TABLE sty_story_has_story_theme');
        $this->addSql('DROP TABLE sty_story_image');
        $this->addSql('DROP TABLE sty_story_image_has_story_theme');
        $this->addSql('DROP TABLE sty_story_theme');
    }
}
