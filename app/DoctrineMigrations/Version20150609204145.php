<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150609204145 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demofony2_citizen_forum ADD max_votes INT NOT NULL');
        $this->addSql('ALTER TABLE demofony2_process_participation ADD max_votes INT NOT NULL');
        $this->addSql('ALTER TABLE demofony2_proposal ADD max_votes INT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demofony2_citizen_forum DROP max_votes');
        $this->addSql('ALTER TABLE demofony2_process_participation DROP max_votes');
        $this->addSql('ALTER TABLE demofony2_proposal DROP max_votes');
    }
}
