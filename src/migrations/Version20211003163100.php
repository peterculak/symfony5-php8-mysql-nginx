<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211003163100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE fact (security_id INTEGER NOT NULL, attribute_id INTEGER NOT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(security_id, attribute_id))');
        $this->addSql('CREATE INDEX IDX_6FA45B956DBE4214 ON fact (security_id)');
        $this->addSql('CREATE INDEX IDX_6FA45B95B6E62EFA ON fact (attribute_id)');
        $this->addSql('CREATE TABLE security (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, symbol VARCHAR(3) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE fact');
        $this->addSql('DROP TABLE security');
    }
}
