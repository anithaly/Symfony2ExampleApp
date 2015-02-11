<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150211213552 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_log_entry ADD object_name VARCHAR(20) NOT NULL, CHANGE is_checked is_checked TINYINT(1) DEFAULT NULL, CHANGE user_id user INT NOT NULL');
        $this->addSql('ALTER TABLE custom_log_entry ADD CONSTRAINT FK_4165018B8D93D649 FOREIGN KEY (user) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_4165018B8D93D649 ON custom_log_entry (user)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_log_entry DROP FOREIGN KEY FK_4165018B8D93D649');
        $this->addSql('DROP INDEX IDX_4165018B8D93D649 ON custom_log_entry');
        $this->addSql('ALTER TABLE custom_log_entry DROP object_name, CHANGE is_checked is_checked TINYINT(1) NOT NULL, CHANGE user user_id INT NOT NULL');
    }
}
