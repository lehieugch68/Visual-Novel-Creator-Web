<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210812064510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_7D3656A4D60322AC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, role_id, username, password, email FROM account');
        $this->addSql('DROP TABLE account');
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER DEFAULT NULL, username VARCHAR(255) DEFAULT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_7D3656A4D60322AC FOREIGN KEY (role_id) REFERENCES account_role (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO account (id, role_id, username, password, email) SELECT id, role_id, username, password, email FROM __temp__account');
        $this->addSql('DROP TABLE __temp__account');
        $this->addSql('CREATE INDEX IDX_7D3656A4D60322AC ON account (role_id)');
        $this->addSql('DROP INDEX IDX_232B318C61220EA6');
        $this->addSql('DROP INDEX IDX_232B318C737F9048');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, creator_id, imagecover_id, title, createdat, description FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER DEFAULT NULL, imagecover_id INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE BINARY, createdat VARCHAR(255) DEFAULT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_232B318C61220EA6 FOREIGN KEY (creator_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_232B318C737F9048 FOREIGN KEY (imagecover_id) REFERENCES game_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game (id, creator_id, imagecover_id, title, createdat, description) SELECT id, creator_id, imagecover_id, title, createdat, description FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
        $this->addSql('CREATE INDEX IDX_232B318C61220EA6 ON game (creator_id)');
        $this->addSql('CREATE INDEX IDX_232B318C737F9048 ON game (imagecover_id)');
        $this->addSql('DROP INDEX UNIQ_2E928A06166053B4');
        $this->addSql('DROP INDEX IDX_2E928A06DCD6AAE9');
        $this->addSql('DROP INDEX IDX_2E928A06328AA66F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_battle_scene AS SELECT id, playericon_id, enemyimage_id, scene_id, playerhp, enemyhp, playeratk, enemyatk FROM game_battle_scene');
        $this->addSql('DROP TABLE game_battle_scene');
        $this->addSql('CREATE TABLE game_battle_scene (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, playericon_id INTEGER DEFAULT NULL, enemyimage_id INTEGER DEFAULT NULL, scene_id INTEGER DEFAULT NULL, playerhp INTEGER DEFAULT NULL, enemyhp INTEGER DEFAULT NULL, playeratk INTEGER DEFAULT NULL, enemyatk INTEGER DEFAULT NULL, CONSTRAINT FK_2E928A06328AA66F FOREIGN KEY (playericon_id) REFERENCES game_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2E928A06DCD6AAE9 FOREIGN KEY (enemyimage_id) REFERENCES game_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2E928A06166053B4 FOREIGN KEY (scene_id) REFERENCES game_scene (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_battle_scene (id, playericon_id, enemyimage_id, scene_id, playerhp, enemyhp, playeratk, enemyatk) SELECT id, playericon_id, enemyimage_id, scene_id, playerhp, enemyhp, playeratk, enemyatk FROM __temp__game_battle_scene');
        $this->addSql('DROP TABLE __temp__game_battle_scene');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E928A06166053B4 ON game_battle_scene (scene_id)');
        $this->addSql('CREATE INDEX IDX_2E928A06DCD6AAE9 ON game_battle_scene (enemyimage_id)');
        $this->addSql('CREATE INDEX IDX_2E928A06328AA66F ON game_battle_scene (playericon_id)');
        $this->addSql('DROP INDEX IDX_F70E7DD016678C77');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_image AS SELECT id, uploader_id, url, filename FROM game_image');
        $this->addSql('DROP TABLE game_image');
        $this->addSql('CREATE TABLE game_image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uploader_id INTEGER DEFAULT NULL, url VARCHAR(255) DEFAULT NULL COLLATE BINARY, filename VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_F70E7DD016678C77 FOREIGN KEY (uploader_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_image (id, uploader_id, url, filename) SELECT id, uploader_id, url, filename FROM __temp__game_image');
        $this->addSql('DROP TABLE __temp__game_image');
        $this->addSql('CREATE INDEX IDX_F70E7DD016678C77 ON game_image (uploader_id)');
        $this->addSql('DROP INDEX IDX_2836BCAFE48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_intro AS SELECT id, game_id, title, content, introorder FROM game_intro');
        $this->addSql('DROP TABLE game_intro');
        $this->addSql('CREATE TABLE game_intro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE BINARY, content VARCHAR(255) DEFAULT NULL COLLATE BINARY, introorder INTEGER DEFAULT NULL, CONSTRAINT FK_2836BCAFE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_intro (id, game_id, title, content, introorder) SELECT id, game_id, title, content, introorder FROM __temp__game_intro');
        $this->addSql('DROP TABLE __temp__game_intro');
        $this->addSql('CREATE INDEX IDX_2836BCAFE48FD905 ON game_intro (game_id)');
        $this->addSql('DROP INDEX IDX_EB4A9655E48FD905');
        $this->addSql('DROP INDEX IDX_EB4A9655C93D69EA');
        $this->addSql('DROP INDEX IDX_EB4A9655399BBB13');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_scene AS SELECT id, game_id, background_id, music_id, sceneorder, isbattle, title, description FROM game_scene');
        $this->addSql('DROP TABLE game_scene');
        $this->addSql('CREATE TABLE game_scene (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, background_id INTEGER DEFAULT NULL, music_id INTEGER DEFAULT NULL, sceneorder INTEGER DEFAULT NULL, isbattle BOOLEAN DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_EB4A9655E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EB4A9655C93D69EA FOREIGN KEY (background_id) REFERENCES game_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_EB4A9655399BBB13 FOREIGN KEY (music_id) REFERENCES game_sound (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_scene (id, game_id, background_id, music_id, sceneorder, isbattle, title, description) SELECT id, game_id, background_id, music_id, sceneorder, isbattle, title, description FROM __temp__game_scene');
        $this->addSql('DROP TABLE __temp__game_scene');
        $this->addSql('CREATE INDEX IDX_EB4A9655E48FD905 ON game_scene (game_id)');
        $this->addSql('CREATE INDEX IDX_EB4A9655C93D69EA ON game_scene (background_id)');
        $this->addSql('CREATE INDEX IDX_EB4A9655399BBB13 ON game_scene (music_id)');
        $this->addSql('DROP INDEX IDX_CABDBA0B16678C77');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_sound AS SELECT id, uploader_id, url, filename FROM game_sound');
        $this->addSql('DROP TABLE game_sound');
        $this->addSql('CREATE TABLE game_sound (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uploader_id INTEGER DEFAULT NULL, url VARCHAR(255) DEFAULT NULL COLLATE BINARY, filename VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_CABDBA0B16678C77 FOREIGN KEY (uploader_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_sound (id, uploader_id, url, filename) SELECT id, uploader_id, url, filename FROM __temp__game_sound');
        $this->addSql('DROP TABLE __temp__game_sound');
        $this->addSql('CREATE INDEX IDX_CABDBA0B16678C77 ON game_sound (uploader_id)');
        $this->addSql('DROP INDEX IDX_DB6C0D7B2048442D');
        $this->addSql('DROP INDEX IDX_DB6C0D7B8453D1C5');
        $this->addSql('DROP INDEX IDX_DB6C0D7BF7EACDE6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_story_scene AS SELECT id, gamescene_id, talkericon_id, characterimage_id, text, contextorder, talker FROM game_story_scene');
        $this->addSql('DROP TABLE game_story_scene');
        $this->addSql('CREATE TABLE game_story_scene (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gamescene_id INTEGER DEFAULT NULL, talkericon_id INTEGER DEFAULT NULL, characterimage_id INTEGER DEFAULT NULL, text CLOB DEFAULT NULL COLLATE BINARY, contextorder INTEGER DEFAULT NULL, talker VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_DB6C0D7BF7EACDE6 FOREIGN KEY (gamescene_id) REFERENCES game_scene (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DB6C0D7B8453D1C5 FOREIGN KEY (talkericon_id) REFERENCES game_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_DB6C0D7B2048442D FOREIGN KEY (characterimage_id) REFERENCES game_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO game_story_scene (id, gamescene_id, talkericon_id, characterimage_id, text, contextorder, talker) SELECT id, gamescene_id, talkericon_id, characterimage_id, text, contextorder, talker FROM __temp__game_story_scene');
        $this->addSql('DROP TABLE __temp__game_story_scene');
        $this->addSql('CREATE INDEX IDX_DB6C0D7B2048442D ON game_story_scene (characterimage_id)');
        $this->addSql('CREATE INDEX IDX_DB6C0D7B8453D1C5 ON game_story_scene (talkericon_id)');
        $this->addSql('CREATE INDEX IDX_DB6C0D7BF7EACDE6 ON game_story_scene (gamescene_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_7D3656A4D60322AC');
        $this->addSql('CREATE TEMPORARY TABLE __temp__account AS SELECT id, role_id, username, password, email FROM account');
        $this->addSql('DROP TABLE account');
        $this->addSql('CREATE TABLE account (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, role_id INTEGER DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO account (id, role_id, username, password, email) SELECT id, role_id, username, password, email FROM __temp__account');
        $this->addSql('DROP TABLE __temp__account');
        $this->addSql('CREATE INDEX IDX_7D3656A4D60322AC ON account (role_id)');
        $this->addSql('DROP INDEX IDX_232B318C61220EA6');
        $this->addSql('DROP INDEX IDX_232B318C737F9048');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game AS SELECT id, creator_id, imagecover_id, title, createdat, description FROM game');
        $this->addSql('DROP TABLE game');
        $this->addSql('CREATE TABLE game (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id INTEGER DEFAULT NULL, imagecover_id INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, createdat VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO game (id, creator_id, imagecover_id, title, createdat, description) SELECT id, creator_id, imagecover_id, title, createdat, description FROM __temp__game');
        $this->addSql('DROP TABLE __temp__game');
        $this->addSql('CREATE INDEX IDX_232B318C61220EA6 ON game (creator_id)');
        $this->addSql('CREATE INDEX IDX_232B318C737F9048 ON game (imagecover_id)');
        $this->addSql('DROP INDEX IDX_2E928A06328AA66F');
        $this->addSql('DROP INDEX IDX_2E928A06DCD6AAE9');
        $this->addSql('DROP INDEX UNIQ_2E928A06166053B4');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_battle_scene AS SELECT id, playericon_id, enemyimage_id, scene_id, playerhp, enemyhp, playeratk, enemyatk FROM game_battle_scene');
        $this->addSql('DROP TABLE game_battle_scene');
        $this->addSql('CREATE TABLE game_battle_scene (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, playericon_id INTEGER DEFAULT NULL, enemyimage_id INTEGER DEFAULT NULL, scene_id INTEGER DEFAULT NULL, playerhp INTEGER DEFAULT NULL, enemyhp INTEGER DEFAULT NULL, playeratk INTEGER DEFAULT NULL, enemyatk INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL COLLATE BINARY, description VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO game_battle_scene (id, playericon_id, enemyimage_id, scene_id, playerhp, enemyhp, playeratk, enemyatk) SELECT id, playericon_id, enemyimage_id, scene_id, playerhp, enemyhp, playeratk, enemyatk FROM __temp__game_battle_scene');
        $this->addSql('DROP TABLE __temp__game_battle_scene');
        $this->addSql('CREATE INDEX IDX_2E928A06328AA66F ON game_battle_scene (playericon_id)');
        $this->addSql('CREATE INDEX IDX_2E928A06DCD6AAE9 ON game_battle_scene (enemyimage_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E928A06166053B4 ON game_battle_scene (scene_id)');
        $this->addSql('DROP INDEX IDX_F70E7DD016678C77');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_image AS SELECT id, uploader_id, url, filename FROM game_image');
        $this->addSql('DROP TABLE game_image');
        $this->addSql('CREATE TABLE game_image (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uploader_id INTEGER DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO game_image (id, uploader_id, url, filename) SELECT id, uploader_id, url, filename FROM __temp__game_image');
        $this->addSql('DROP TABLE __temp__game_image');
        $this->addSql('CREATE INDEX IDX_F70E7DD016678C77 ON game_image (uploader_id)');
        $this->addSql('DROP INDEX IDX_2836BCAFE48FD905');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_intro AS SELECT id, game_id, title, content, introorder FROM game_intro');
        $this->addSql('DROP TABLE game_intro');
        $this->addSql('CREATE TABLE game_intro (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, introorder INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO game_intro (id, game_id, title, content, introorder) SELECT id, game_id, title, content, introorder FROM __temp__game_intro');
        $this->addSql('DROP TABLE __temp__game_intro');
        $this->addSql('CREATE INDEX IDX_2836BCAFE48FD905 ON game_intro (game_id)');
        $this->addSql('DROP INDEX IDX_EB4A9655E48FD905');
        $this->addSql('DROP INDEX IDX_EB4A9655C93D69EA');
        $this->addSql('DROP INDEX IDX_EB4A9655399BBB13');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_scene AS SELECT id, game_id, background_id, music_id, sceneorder, isbattle, title, description FROM game_scene');
        $this->addSql('DROP TABLE game_scene');
        $this->addSql('CREATE TABLE game_scene (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, game_id INTEGER DEFAULT NULL, background_id INTEGER DEFAULT NULL, music_id INTEGER DEFAULT NULL, sceneorder INTEGER DEFAULT NULL, isbattle BOOLEAN DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO game_scene (id, game_id, background_id, music_id, sceneorder, isbattle, title, description) SELECT id, game_id, background_id, music_id, sceneorder, isbattle, title, description FROM __temp__game_scene');
        $this->addSql('DROP TABLE __temp__game_scene');
        $this->addSql('CREATE INDEX IDX_EB4A9655E48FD905 ON game_scene (game_id)');
        $this->addSql('CREATE INDEX IDX_EB4A9655C93D69EA ON game_scene (background_id)');
        $this->addSql('CREATE INDEX IDX_EB4A9655399BBB13 ON game_scene (music_id)');
        $this->addSql('DROP INDEX IDX_CABDBA0B16678C77');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_sound AS SELECT id, uploader_id, url, filename FROM game_sound');
        $this->addSql('DROP TABLE game_sound');
        $this->addSql('CREATE TABLE game_sound (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uploader_id INTEGER DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO game_sound (id, uploader_id, url, filename) SELECT id, uploader_id, url, filename FROM __temp__game_sound');
        $this->addSql('DROP TABLE __temp__game_sound');
        $this->addSql('CREATE INDEX IDX_CABDBA0B16678C77 ON game_sound (uploader_id)');
        $this->addSql('DROP INDEX IDX_DB6C0D7BF7EACDE6');
        $this->addSql('DROP INDEX IDX_DB6C0D7B8453D1C5');
        $this->addSql('DROP INDEX IDX_DB6C0D7B2048442D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__game_story_scene AS SELECT id, gamescene_id, talkericon_id, characterimage_id, text, contextorder, talker FROM game_story_scene');
        $this->addSql('DROP TABLE game_story_scene');
        $this->addSql('CREATE TABLE game_story_scene (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, gamescene_id INTEGER DEFAULT NULL, talkericon_id INTEGER DEFAULT NULL, characterimage_id INTEGER DEFAULT NULL, text CLOB DEFAULT NULL, contextorder INTEGER DEFAULT NULL, talker VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO game_story_scene (id, gamescene_id, talkericon_id, characterimage_id, text, contextorder, talker) SELECT id, gamescene_id, talkericon_id, characterimage_id, text, contextorder, talker FROM __temp__game_story_scene');
        $this->addSql('DROP TABLE __temp__game_story_scene');
        $this->addSql('CREATE INDEX IDX_DB6C0D7BF7EACDE6 ON game_story_scene (gamescene_id)');
        $this->addSql('CREATE INDEX IDX_DB6C0D7B8453D1C5 ON game_story_scene (talkericon_id)');
        $this->addSql('CREATE INDEX IDX_DB6C0D7B2048442D ON game_story_scene (characterimage_id)');
    }
}
