<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522192741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_by_object AS SELECT id, object_id, event_id, action1, action2, action3, action4, action5, location FROM event_by_object');
        $this->addSql('DROP TABLE event_by_object');
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, event_id INTEGER NOT NULL, action1 INTEGER DEFAULT NULL, action2 INTEGER DEFAULT NULL, action3 INTEGER DEFAULT NULL, action4 INTEGER DEFAULT NULL, action5 INTEGER DEFAULT NULL, location INTEGER NOT NULL)');
        $this->addSql('INSERT INTO event_by_object (id, object_id, event_id, action1, action2, action3, action4, action5, location) SELECT id, object_id, event_id, action1, action2, action3, action4, action5, location FROM __temp__event_by_object');
        $this->addSql('DROP TABLE __temp__event_by_object');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, room_id, game_id, pos_x, pos_y, start FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room_id INTEGER NOT NULL, game_id INTEGER NOT NULL, pos_x INTEGER NOT NULL, pos_y INTEGER NOT NULL, start BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO game (id, room_id, game_id, pos_x, pos_y, start) SELECT id, room_id, game_id, pos_x, pos_y, start FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, room_id INTEGER NOT NULL, sequence INTEGER NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, position_z INTEGER NOT NULL, width INTEGER DEFAULT NULL, height INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence, position_x, position_y, position_z, width, height) SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_by_object AS SELECT id, object_id, event_id, location, action1, action2, action3, action4, action5 FROM event_by_object');
        $this->addSql('DROP TABLE event_by_object');
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER DEFAULT NULL, event_id INTEGER DEFAULT NULL, action1 INTEGER DEFAULT NULL, action2 INTEGER DEFAULT NULL, action3 INTEGER DEFAULT NULL, action4 INTEGER DEFAULT NULL, action5 INTEGER DEFAULT NULL, location INTEGER NOT NULL, CONSTRAINT FK_6DE49A1B232D562B FOREIGN KEY (object_id) REFERENCES game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B9D9AD153 FOREIGN KEY (action1) REFERENCES "action" (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B49380E9 FOREIGN KEY (action2) REFERENCES "action" (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B7394B07F FOREIGN KEY (action3) REFERENCES "action" (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1BEDF025DC FOREIGN KEY (action4) REFERENCES "action" (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B9AF7154A FOREIGN KEY (action5) REFERENCES "action" (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event_by_object (id, object_id, event_id, location, action1, action2, action3, action4, action5) SELECT id, object_id, event_id, location, action1, action2, action3, action4, action5 FROM __temp__event_by_object');
        $this->addSql('DROP TABLE __temp__event_by_object');
        $this->addSql('CREATE INDEX IDX_6DE49A1B9AF7154A ON event_by_object (action5)');
        $this->addSql('CREATE INDEX IDX_6DE49A1BEDF025DC ON event_by_object (action4)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B7394B07F ON event_by_object (action3)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B49380E9 ON event_by_object (action2)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B9D9AD153 ON event_by_object (action1)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B71F7E88B ON event_by_object (event_id)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B232D562B ON event_by_object (object_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, game_id, room_id, pos_x, pos_y, start FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room_id INTEGER DEFAULT NULL, game_id INTEGER NOT NULL, pos_x INTEGER NOT NULL, pos_y INTEGER NOT NULL, start BOOLEAN DEFAULT NULL, CONSTRAINT FK_232B318C54177093 FOREIGN KEY (room_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game (id, game_id, room_id, pos_x, pos_y, start) SELECT id, game_id, room_id, pos_x, pos_y, start FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
        $this->addSql('CREATE INDEX IDX_232B318C54177093 ON game (room_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER DEFAULT NULL, room_id INTEGER DEFAULT NULL, sequence INTEGER NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, position_z INTEGER NOT NULL, width INTEGER DEFAULT NULL, height INTEGER DEFAULT NULL, CONSTRAINT FK_44BE8886232D562B FOREIGN KEY (object_id) REFERENCES game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_44BE888654177093 FOREIGN KEY (room_id) REFERENCES room (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence, position_x, position_y, position_z, width, height) SELECT id, object_id, room_id, sequence, position_x, position_y, position_z, width, height FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
        $this->addSql('CREATE INDEX IDX_44BE888654177093 ON object_by_room (room_id)');
        $this->addSql('CREATE INDEX IDX_44BE8886232D562B ON object_by_room (object_id)');
    }
}
