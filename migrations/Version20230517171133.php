<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517171133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_object AS SELECT id, image, name, clickable FROM game_object');
        $this->addSql('DROP TABLE game_object');
        $this->addSql('CREATE TABLE game_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, clickable BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO game_object (id, image, name, clickable) SELECT id, image, name, clickable FROM __temp__game_object');
        $this->addSql('DROP TABLE __temp__game_object');
        $this->addSql('ALTER TABLE object_by_room ADD COLUMN position_x INTEGER NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE object_by_room ADD COLUMN position_y INTEGER NOT NULL DEFAULT 0');
        $this->addSql('ALTER TABLE object_by_room ADD COLUMN position_z INTEGER NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_object ADD COLUMN position_x INTEGER NOT NULL');
        $this->addSql('ALTER TABLE game_object ADD COLUMN position_y INTEGER NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, room_id INTEGER NOT NULL, sequence INTEGER NOT NULL)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence) SELECT id, object_id, room_id, sequence FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
    }
}
