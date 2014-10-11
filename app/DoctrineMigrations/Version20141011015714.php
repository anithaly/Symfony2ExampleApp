<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141011015714 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE Publication ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Publication ADD CONSTRAINT FK_29A0E8AE12469DE2 FOREIGN KEY (category_id) REFERENCES Category (id)');
        $this->addSql('CREATE INDEX IDX_29A0E8AE12469DE2 ON Publication (category_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('ALTER TABLE Publication DROP FOREIGN KEY FK_29A0E8AE12469DE2');
        $this->addSql('DROP INDEX IDX_29A0E8AE12469DE2 ON Publication');
        $this->addSql('ALTER TABLE Publication DROP category_id');
    }
}
