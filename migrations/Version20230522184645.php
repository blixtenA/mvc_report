<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522184645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER DEFAULT NULL, room_id INTEGER DEFAULT NULL, sequence INTEGER NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, position_z INTEGER NOT NULL, width INTEGER DEFAULT NULL, height INTEGER DEFAULT NULL, CONSTRAINT FK_44BE8886232D562B FOREIGN KEY (object_id) REFERENCES game_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_44BE888654177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence, position_x, position_y, position_z, width, height) SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
        $this->addSql('CREATE INDEX IDX_44BE8886232D562B ON object_by_room (object_id)');
        $this->addSql('CREATE INDEX IDX_44BE888654177093 ON object_by_room (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, room_id INTEGER NOT NULL, sequence INTEGER NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, position_z INTEGER NOT NULL, width INTEGER DEFAULT NULL, height INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence, position_x, position_y, position_z, width, height) SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
    }
}
