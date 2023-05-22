<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522185412 extends AbstractMigration
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
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER DEFAULT NULL, event_id INTEGER DEFAULT NULL, action1 INTEGER DEFAULT NULL, action2 INTEGER DEFAULT NULL, action3 INTEGER DEFAULT NULL, action4 INTEGER DEFAULT NULL, action5 INTEGER DEFAULT NULL, location INTEGER NOT NULL, CONSTRAINT FK_6DE49A1B232D562B FOREIGN KEY (object_id) REFERENCES game_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B9D9AD153 FOREIGN KEY (action1) REFERENCES "action" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B49380E9 FOREIGN KEY (action2) REFERENCES "action" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B7394B07F FOREIGN KEY (action3) REFERENCES "action" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1BEDF025DC FOREIGN KEY (action4) REFERENCES "action" (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6DE49A1B9AF7154A FOREIGN KEY (action5) REFERENCES "action" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event_by_object (id, object_id, event_id, location, action1, action2, action3, action4, action5) SELECT id, object_id, event_id, location, action1, action2, action3, action4, action5 FROM __temp__event_by_object');
        $this->addSql('DROP TABLE __temp__event_by_object');
        $this->addSql('CREATE INDEX IDX_6DE49A1B232D562B ON event_by_object (object_id)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B71F7E88B ON event_by_object (event_id)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B9D9AD153 ON event_by_object (action1)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B49380E9 ON event_by_object (action2)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B7394B07F ON event_by_object (action3)');
        $this->addSql('CREATE INDEX IDX_6DE49A1BEDF025DC ON event_by_object (action4)');
        $this->addSql('CREATE INDEX IDX_6DE49A1B9AF7154A ON event_by_object (action5)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_by_object AS SELECT id, object_id, event_id, action1, action2, action3, action4, action5, location FROM event_by_object');
        $this->addSql('DROP TABLE event_by_object');
        $this->addSql('CREATE TABLE event_by_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, object_id INTEGER NOT NULL, event_id INTEGER NOT NULL, action1 INTEGER DEFAULT NULL, action2 INTEGER DEFAULT NULL, action3 INTEGER DEFAULT NULL, action4 INTEGER DEFAULT NULL, action5 INTEGER DEFAULT NULL, location INTEGER NOT NULL)');
        $this->addSql('INSERT INTO event_by_object (id, object_id, event_id, action1, action2, action3, action4, action5, location) SELECT id, object_id, event_id, action1, action2, action3, action4, action5, location FROM __temp__event_by_object');
        $this->addSql('DROP TABLE __temp__event_by_object');
    }
}
