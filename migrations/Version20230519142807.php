<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519142807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_by_object AS SELECT id, object_id, event_id, location, action1, action2, action3, action4, action5 FROM event_by_object');
        $this->addSql('DROP TABLE event_by_object');
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, event_id INTEGER NOT NULL, location INTEGER NOT NULL, action1 INTEGER DEFAULT NULL, action2 INTEGER DEFAULT NULL, action3 INTEGER DEFAULT NULL, action4 INTEGER DEFAULT NULL, action5 INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO event_by_object (id, object_id, event_id, location, action1, action2, action3, action4, action5) SELECT id, object_id, event_id, location, action1, action2, action3, action4, action5 FROM __temp__event_by_object');
        $this->addSql('DROP TABLE __temp__event_by_object');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_by_object AS SELECT id, object_id, event_id, location, action1, action2, action3, action4, action5 FROM event_by_object');
        $this->addSql('DROP TABLE event_by_object');
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, event_id INTEGER NOT NULL, location VARCHAR(255) NOT NULL, action1 INTEGER DEFAULT NULL, action2 INTEGER DEFAULT NULL, action3 INTEGER DEFAULT NULL, action4 INTEGER DEFAULT NULL, action5 INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO event_by_object (id, object_id, event_id, location, action1, action2, action3, action4, action5) SELECT id, object_id, event_id, location, action1, action2, action3, action4, action5 FROM __temp__event_by_object');
        $this->addSql('DROP TABLE __temp__event_by_object');
    }
}
