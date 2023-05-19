<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517174054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_object ADD COLUMN image2 VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence, position_x, position_y, position_z FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, room_id INTEGER NOT NULL, sequence INTEGER NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, position_z INTEGER NOT NULL)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence, position_x, position_y, position_z) SELECT id, object_id, room_id, sequence, position_x, position_y, position_z FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_object AS SELECT id, image, name, clickable FROM game_object');
        $this->addSql('DROP TABLE game_object');
        $this->addSql('CREATE TABLE game_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, clickable BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO game_object (id, image, name, clickable) SELECT id, image, name, clickable FROM __temp__game_object');
        $this->addSql('DROP TABLE __temp__game_object');
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence, position_x, position_y, position_z FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, room_id INTEGER NOT NULL, sequence INTEGER NOT NULL, position_x INTEGER DEFAULT 0 NOT NULL, position_y INTEGER DEFAULT 0 NOT NULL, position_z INTEGER DEFAULT 0 NOT NULL)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence, position_x, position_y, position_z) SELECT id, object_id, room_id, sequence, position_x, position_y, position_z FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
    }
}
