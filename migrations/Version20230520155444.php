<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520155444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE object_by_room ADD COLUMN width INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE object_by_room ADD COLUMN height INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__object_by_room AS SELECT id, object_id, room_id, sequence, position_x, position_y, position_z FROM object_by_room');
        $this->addSql('DROP TABLE object_by_room');
        $this->addSql('CREATE TABLE object_by_room (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, room_id INTEGER NOT NULL, sequence INTEGER NOT NULL, position_x INTEGER NOT NULL, position_y INTEGER NOT NULL, position_z INTEGER NOT NULL)');
        $this->addSql('INSERT INTO object_by_room (id, object_id, room_id, sequence, position_x, position_y, position_z) SELECT id, object_id, room_id, sequence, position_x, position_y, position_z FROM __temp__object_by_room');
        $this->addSql('DROP TABLE __temp__object_by_room');
    }
}
