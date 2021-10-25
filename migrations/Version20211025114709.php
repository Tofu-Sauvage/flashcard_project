<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211025114709 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE card (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, category_id INTEGER NOT NULL, question VARCHAR(255) NOT NULL, answer VARCHAR(255) NOT NULL, tense VARCHAR(255) DEFAULT NULL, mood VARCHAR(255) DEFAULT NULL, sentence1 CLOB DEFAULT NULL, sentence2 CLOB DEFAULT NULL, image CLOB DEFAULT NULL, created_at DATETIME DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_161498D3F675F31B ON card (author_id)');
        $this->addSql('CREATE INDEX IDX_161498D312469DE2 ON card (category_id)');
        $this->addSql('CREATE TABLE card_deck (card_id INTEGER NOT NULL, deck_id INTEGER NOT NULL, PRIMARY KEY(card_id, deck_id))');
        $this->addSql('CREATE INDEX IDX_A39F34954ACC9A20 ON card_deck (card_id)');
        $this->addSql('CREATE INDEX IDX_A39F3495111948DC ON card_deck (deck_id)');
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE deck (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, langague_learn_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, created_at DATETIME DEFAULT NULL, public BOOLEAN NOT NULL, tags CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_4FAC3637F675F31B ON deck (author_id)');
        $this->addSql('CREATE INDEX IDX_4FAC36373810B601 ON deck (langague_learn_id)');
        $this->addSql('CREATE TABLE language (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, flag CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE message (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, sender VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, content CLOB NOT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, language_native_id INTEGER NOT NULL, language_learn_id INTEGER NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, image CLOB DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE INDEX IDX_8D93D6497C3F3181 ON user (language_native_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495509A2A7 ON user (language_learn_id)');
        $this->addSql('CREATE TABLE favorites_fans (user_id INTEGER NOT NULL, deck_id INTEGER NOT NULL, PRIMARY KEY(user_id, deck_id))');
        $this->addSql('CREATE INDEX IDX_DFAD2E09A76ED395 ON favorites_fans (user_id)');
        $this->addSql('CREATE INDEX IDX_DFAD2E09111948DC ON favorites_fans (deck_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE card_deck');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE deck');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE favorites_fans');
    }
}
