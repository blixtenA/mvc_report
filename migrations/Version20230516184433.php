<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516184433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, background, game_objects, doors, name, description FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, background VARCHAR(255) NOT NULL, game_objects CLOB DEFAULT NULL --(DC2Type:array)
        , doors CLOB DEFAULT NULL --(DC2Type:array)
        , name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO room (id, background, game_objects, doors, name, description) SELECT id, background, game_objects, doors, name, description FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__room AS SELECT id, background, game_objects, doors, name, description FROM room');
        $this->addSql('DROP TABLE room');
        $this->addSql('CREATE TABLE room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, background VARCHAR(255) NOT NULL, game_objects CLOB DEFAULT NULL --(DC2Type:array)
        , doors CLOB NOT NULL --(DC2Type:array)
        , name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO room (id, background, game_objects, doors, name, description) SELECT id, background, game_objects, doors, name, description FROM __temp__room');
        $this->addSql('DROP TABLE __temp__room');
    }
}
