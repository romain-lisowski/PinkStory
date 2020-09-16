<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916213012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE msgr_message (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E051654AFB7336F0 ON msgr_message (queue_name)');
        $this->addSql('CREATE INDEX IDX_E051654AE3BD61CE ON msgr_message (available_at)');
        $this->addSql('CREATE INDEX IDX_E051654A16BA31DB ON msgr_message (delivered_at)');
        $this->addSql('LOCK TABLE msgr_message;');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_msgr_message() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'msgr_message\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON msgr_message;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT ON msgr_message FOR EACH ROW EXECUTE PROCEDURE notify_msgr_message();');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE msgr_message');
    }
}
