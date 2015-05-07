<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150507185334 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B310AE949E5A');
        $this->addSql('DROP INDEX IDX_4FB8B310AE949E5A ON demofony2_document');
        $this->addSql('ALTER TABLE demofony2_document ADD institutional_document_proposal_id INT DEFAULT NULL, ADD institutional_document_citizen_forum_id INT DEFAULT NULL, CHANGE institutional_answer_id institutional_document_process_participation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B31092FF0941 FOREIGN KEY (institutional_document_process_participation_id) REFERENCES demofony2_process_participation (id)');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B310937A99C0 FOREIGN KEY (institutional_document_proposal_id) REFERENCES demofony2_proposal (id)');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B310DAF86E8E FOREIGN KEY (institutional_document_citizen_forum_id) REFERENCES demofony2_citizen_forum (id)');
        $this->addSql('CREATE INDEX IDX_4FB8B31092FF0941 ON demofony2_document (institutional_document_process_participation_id)');
        $this->addSql('CREATE INDEX IDX_4FB8B310937A99C0 ON demofony2_document (institutional_document_proposal_id)');
        $this->addSql('CREATE INDEX IDX_4FB8B310DAF86E8E ON demofony2_document (institutional_document_citizen_forum_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B31092FF0941');
        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B310937A99C0');
        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B310DAF86E8E');
        $this->addSql('DROP INDEX IDX_4FB8B31092FF0941 ON demofony2_document');
        $this->addSql('DROP INDEX IDX_4FB8B310937A99C0 ON demofony2_document');
        $this->addSql('DROP INDEX IDX_4FB8B310DAF86E8E ON demofony2_document');
        $this->addSql('ALTER TABLE demofony2_document ADD institutional_answer_id INT DEFAULT NULL, DROP institutional_document_process_participation_id, DROP institutional_document_proposal_id, DROP institutional_document_citizen_forum_id');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B310AE949E5A FOREIGN KEY (institutional_answer_id) REFERENCES demofony2_institutional_answer (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_4FB8B310AE949E5A ON demofony2_document (institutional_answer_id)');
    }
}
