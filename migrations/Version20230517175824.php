<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517175824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__action AS SELECT id, event_action FROM "action"');
        $this->addSql('DROP TABLE "action"');
        $this->addSql('CREATE TABLE "action" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_action VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO "action" (id, event_action) SELECT id, event_action FROM __temp__action');
        $this->addSql('DROP TABLE __temp__action');
        $this->addSql('ALTER TABLE event_by_object ADD COLUMN action1 INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE event_by_object ADD COLUMN action2 INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE event_by_object ADD COLUMN action3 INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE event_by_object ADD COLUMN action4 INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE event_by_object ADD COLUMN action5 INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "action" ADD COLUMN event_id INTEGER NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_by_object AS SELECT id, object_id, event_id, location FROM event_by_object');
        $this->addSql('DROP TABLE event_by_object');
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, event_id INTEGER NOT NULL, location VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO event_by_object (id, object_id, event_id, location) SELECT id, object_id, event_id, location FROM __temp__event_by_object');
        $this->addSql('DROP TABLE __temp__event_by_object');
    }
}
