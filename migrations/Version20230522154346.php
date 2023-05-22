<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522154346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_object AS SELECT id, image, name, image2, effect FROM game_object');
        $this->addSql('DROP TABLE game_object');
        $this->addSql('CREATE TABLE game_object (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, image VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, image2 VARCHAR(255) DEFAULT NULL, effect VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO game_object (id, image, name, image2, effect) SELECT id, image, name, image2, effect FROM __temp__game_object');
        $this->addSql('DROP TABLE __temp__game_object');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_object ADD COLUMN clickable BOOLEAN NOT NULL');
    }
}
