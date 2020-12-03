<?php

declare(strict_types=1);

namespace App\Migration\Doctrine;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202103029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sty_story_rating (id UUID NOT NULL, story_id UUID NOT NULL, user_id UUID NOT NULL, activated BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, rate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_10A90F8FAA5D4036 ON sty_story_rating (story_id)');
        $this->addSql('CREATE INDEX IDX_10A90F8FA76ED395 ON sty_story_rating (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_STORY_RATING ON sty_story_rating (story_id, user_id)');
        $this->addSql('ALTER TABLE sty_story_rating ADD CONSTRAINT FK_10A90F8FAA5D4036 FOREIGN KEY (story_id) REFERENCES sty_story (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sty_story_rating ADD CONSTRAINT FK_10A90F8FA76ED395 FOREIGN KEY (user_id) REFERENCES usr_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sty_story_rating');
    }
}
