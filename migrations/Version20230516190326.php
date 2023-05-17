<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516190326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_object AS SELECT id, image, position_x, position_y, name, clickable, options, events FROM game_object');
        $this->addSql('DROP TABLE game_object');
        $this->addSql('CREATE TABLE game_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image VARCHAR(255) NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, name VARCHAR(255) NOT NULL, clickable BOOLEAN NOT NULL, options CLOB DEFAULT NULL --(DC2Type:json)
        , events CLOB DEFAULT NULL --(DC2Type:array)
        )');
        $this->addSql('INSERT INTO game_object (id, image, position_x, position_y, name, clickable, options, events) SELECT id, image, position_x, position_y, name, clickable, options, events FROM __temp__game_object');
        $this->addSql('DROP TABLE __temp__game_object');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_object AS SELECT id, image, position_x, position_y, name, clickable, options, events FROM game_object');
        $this->addSql('DROP TABLE game_object');
        $this->addSql('CREATE TABLE game_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image VARCHAR(255) NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, name VARCHAR(255) NOT NULL, clickable BOOLEAN NOT NULL, options CLOB DEFAULT NULL --(DC2Type:json)
        , events CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO game_object (id, image, position_x, position_y, name, clickable, options, events) SELECT id, image, position_x, position_y, name, clickable, options, events FROM __temp__game_object');
        $this->addSql('DROP TABLE __temp__game_object');
    }
}
