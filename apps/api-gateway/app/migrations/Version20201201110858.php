<?php

declare(strict_types=1);

namespace App\Migration;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201110858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sty_story DROP CONSTRAINT FK_AE61A82B3E403BF1');
        $this->addSql('ALTER TABLE sty_story ADD CONSTRAINT FK_AE61A82B3E403BF1 FOREIGN KEY (story_image_id) REFERENCES sty_story_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sty_story DROP CONSTRAINT fk_ae61a82b3e403bf1');
        $this->addSql('ALTER TABLE sty_story ADD CONSTRAINT fk_ae61a82b3e403bf1 FOREIGN KEY (story_image_id) REFERENCES sty_story_image (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
