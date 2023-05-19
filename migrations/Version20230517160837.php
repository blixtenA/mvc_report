<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517160837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "action" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_id INTEGER NOT NULL, event_action VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, event_id INTEGER NOT NULL, location VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE game_map (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER NOT NULL, room_id INTEGER NOT NULL, pos_x INTEGER NOT NULL, pos_y INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, room_id INTEGER NOT NULL, sequence INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE text_by_object_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, event_id INTEGER NOT NULL, text VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, text, name FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, text VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO event (id, text, name) SELECT id, text, name FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_object AS SELECT id, image, position_x, position_y, name, clickable FROM game_object');
        $this->addSql('DROP TABLE game_object');
        $this->addSql('CREATE TABLE game_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image VARCHAR(255) NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, name VARCHAR(255) NOT NULL, clickable BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO game_object (id, image, position_x, position_y, name, clickable) SELECT id, image, position_x, position_y, name, clickable FROM __temp__game_object');
        $this->addSql('DROP TABLE __temp__game_object');
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, background, name, description FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, background VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO room (id, background, name, description) SELECT id, background, name, description FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "action"');
        $this->addSql('DROP TABLE event_by_object');
        $this->addSql('DROP TABLE game_map');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('DROP TABLE text_by_object_event');
        $this->addSql('ALTER TABLE event ADD COLUMN event_images CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN position_x INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN position_y INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN animation_delay INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN route VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE event ADD COLUMN actions VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game_object ADD COLUMN options VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD COLUMN game_objects CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE room ADD COLUMN doors CLOB DEFAULT NULL');
    }
}
