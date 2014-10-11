<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20141011022859 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('CREATE TABLE publications_tag (publication_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_412BEDB138B217A7 (publication_id), INDEX IDX_412BEDB1BAD26311 (tag_id), PRIMARY KEY(publication_id, tag_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publications_tag ADD CONSTRAINT FK_412BEDB138B217A7 FOREIGN KEY (publication_id) REFERENCES Publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publications_tag ADD CONSTRAINT FK_412BEDB1BAD26311 FOREIGN KEY (tag_id) REFERENCES Tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        
        $this->addSql('DROP TABLE publications_tag');
    }
}
