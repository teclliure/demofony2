<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150507125607 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE demofony2_institutional_answer_documents');
        $this->addSql('ALTER TABLE demofony2_document ADD institutional_document_citizen_forum_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B310DAF86E8E FOREIGN KEY (institutional_document_citizen_forum_id) REFERENCES demofony2_citizen_forum (id)');
        $this->addSql('CREATE INDEX IDX_4FB8B310DAF86E8E ON demofony2_document (institutional_document_citizen_forum_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demofony2_institutional_answer_documents (institutional_answer_id INT NOT NULL, document_id INT NOT NULL, UNIQUE INDEX UNIQ_1AAE40DAC33F7837 (document_id), INDEX IDX_1AAE40DAAE949E5A (institutional_answer_id), PRIMARY KEY(institutional_answer_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demofony2_institutional_answer_documents ADD CONSTRAINT FK_1AAE40DAAE949E5A FOREIGN KEY (institutional_answer_id) REFERENCES demofony2_institutional_answer (id)');
        $this->addSql('ALTER TABLE demofony2_institutional_answer_documents ADD CONSTRAINT FK_1AAE40DAC33F7837 FOREIGN KEY (document_id) REFERENCES demofony2_document (id)');
        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B310DAF86E8E');
        $this->addSql('DROP INDEX IDX_4FB8B310DAF86E8E ON demofony2_document');
        $this->addSql('ALTER TABLE demofony2_document DROP institutional_document_citizen_forum_id');
    }
}
