<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250503151543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__schedule AS SELECT id, week, created_at FROM schedule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE schedule
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE schedule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, employee_id INTEGER DEFAULT NULL, week DATE NOT NULL, created_at DATETIME NOT NULL, CONSTRAINT FK_5A3811FB8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO schedule (id, week, created_at) SELECT id, week, created_at FROM __temp__schedule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__schedule
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5A3811FB8C03F15C ON schedule (employee_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__schedule AS SELECT id, week, created_at FROM schedule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE schedule
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE schedule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, week DATE NOT NULL, created_at DATETIME NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO schedule (id, week, created_at) SELECT id, week, created_at FROM __temp__schedule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__schedule
        SQL);
    }
}
