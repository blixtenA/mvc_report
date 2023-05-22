<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522184017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, room_id, game_id, pos_x, pos_y, start FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room_id INTEGER DEFAULT NULL, game_id INTEGER NOT NULL, pos_x INTEGER NOT NULL, pos_y INTEGER NOT NULL, start BOOLEAN DEFAULT NULL, CONSTRAINT FK_232B318C54177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game (id, room_id, game_id, pos_x, pos_y, start) SELECT id, room_id, game_id, pos_x, pos_y, start FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
        $this->addSql('CREATE INDEX IDX_232B318C54177093 ON game (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, room_id, game_id, pos_x, pos_y, start FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room_id INTEGER NOT NULL, game_id INTEGER NOT NULL, pos_x INTEGER NOT NULL, pos_y INTEGER NOT NULL, start BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO game (id, room_id, game_id, pos_x, pos_y, start) SELECT id, room_id, game_id, pos_x, pos_y, start FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
    }
}
