<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230521133225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "action" ADD COLUMN option_yes INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE "action" ADD COLUMN option_no INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE "action" ADD COLUMN option_object INTEGER DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__action AS SELECT id, event_action, text FROM "action"');
        $this->addSql('DROP TABLE "action"');
        $this->addSql('CREATE TABLE "action" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event_action VARCHAR(255) NOT NULL, text VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO "action" (id, event_action, text) SELECT id, event_action, text FROM __temp__action');
        $this->addSql('DROP TABLE __temp__action');
    }
}
