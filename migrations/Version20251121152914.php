<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251121152914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tampon_event AS SELECT id, "action", created_at, user_id FROM tampon_event');
        $this->addSql('DROP TABLE tampon_event');
        $this->addSql('CREATE TABLE tampon_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "action" VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_CB6FBB3EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tampon_event (id, "action", created_at, user_id) SELECT id, "action", created_at, user_id FROM __temp__tampon_event');
        $this->addSql('DROP TABLE __temp__tampon_event');
        $this->addSql('CREATE INDEX IDX_CB6FBB3EA76ED395 ON tampon_event (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__tampon_event AS SELECT id, "action", created_at, user_id FROM tampon_event');
        $this->addSql('DROP TABLE tampon_event');
        $this->addSql('CREATE TABLE tampon_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, "action" VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_CB6FBB3EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO tampon_event (id, "action", created_at, user_id) SELECT id, "action", created_at, user_id FROM __temp__tampon_event');
        $this->addSql('DROP TABLE __temp__tampon_event');
        $this->addSql('CREATE INDEX IDX_CB6FBB3EA76ED395 ON tampon_event (user_id)');
    }
}
