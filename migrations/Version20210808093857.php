<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210808093857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_7D3656A4D60322AC ON account (role_id)');
        $this->addSql('CREATE TABLE account_role (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER DEFAULT NULL, imagecover_id INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, createdat VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_232B318C61220EA6 ON game (creator_id)');
        $this->addSql('CREATE INDEX IDX_232B318C737F9048 ON game (imagecover_id)');
        $this->addSql('CREATE TABLE game_image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE game_intro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_2836BCAFE48FD905 ON game_intro (game_id)');
        $this->addSql('CREATE TABLE game_scene (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, background_id INTEGER DEFAULT NULL, music_id INTEGER DEFAULT NULL, sceneorder INTEGER DEFAULT NULL, isbattle BOOLEAN DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_EB4A9655E48FD905 ON game_scene (game_id)');
        $this->addSql('CREATE INDEX IDX_EB4A9655C93D69EA ON game_scene (background_id)');
        $this->addSql('CREATE INDEX IDX_EB4A9655399BBB13 ON game_scene (music_id)');
        $this->addSql('CREATE TABLE game_sound (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, url VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE account_role');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_image');
        $this->addSql('DROP TABLE game_intro');
        $this->addSql('DROP TABLE game_scene');
        $this->addSql('DROP TABLE game_sound');
    }
}
