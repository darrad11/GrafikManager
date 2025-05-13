<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250501193211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE contract (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(80) NOT NULL, max_hours INTEGER DEFAULT NULL, min_break_hours INTEGER DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE leave (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, employee_id INTEGER DEFAULT NULL, start_date DATETIME DEFAULT NULL, end_date DATETIME DEFAULT NULL, type VARCHAR(100) DEFAULT NULL, CONSTRAINT FK_9BB080D08C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9BB080D08C03F15C ON leave (employee_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE schedule (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, week DATE NOT NULL, created_at DATETIME NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE shift (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, employee_id INTEGER NOT NULL, schedule_id INTEGER DEFAULT NULL, date DATETIME NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, CONSTRAINT FK_A50B3B458C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A50B3B45A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A50B3B458C03F15C ON shift (employee_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A50B3B45A40BC2D5 ON shift (schedule_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE shift_template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL, start_time TIME DEFAULT NULL, end_time TIME DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(30) NOT NULL, password VARCHAR(30) NOT NULL, roles CLOB DEFAULT NULL --(DC2Type:json)
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE contract
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE leave
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE schedule
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE shift
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE shift_template
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
