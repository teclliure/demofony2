<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150623113307 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE demofony2_newsletter_citizen_forum (newsletter_id INT NOT NULL, citizen_forum_id INT NOT NULL, INDEX IDX_C5BB28D522DB1917 (newsletter_id), INDEX IDX_C5BB28D5730E4076 (citizen_forum_id), PRIMARY KEY(newsletter_id, citizen_forum_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_newsletter_citizen_initiatives (newsletter_id INT NOT NULL, citizen_initiatives_id INT NOT NULL, INDEX IDX_3DF33BEC22DB1917 (newsletter_id), INDEX IDX_3DF33BECB076B6FC (citizen_initiatives_id), PRIMARY KEY(newsletter_id, citizen_initiatives_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demofony2_newsletter_citizen_forum ADD CONSTRAINT FK_C5BB28D522DB1917 FOREIGN KEY (newsletter_id) REFERENCES demofony2_newsletter (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_citizen_forum ADD CONSTRAINT FK_C5BB28D5730E4076 FOREIGN KEY (citizen_forum_id) REFERENCES demofony2_citizen_forum (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_citizen_initiatives ADD CONSTRAINT FK_3DF33BEC22DB1917 FOREIGN KEY (newsletter_id) REFERENCES demofony2_newsletter (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_citizen_initiatives ADD CONSTRAINT FK_3DF33BECB076B6FC FOREIGN KEY (citizen_initiatives_id) REFERENCES demofony2_citizen_initiative (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE demofony2_newsletter_citizen_forum');
        $this->addSql('DROP TABLE demofony2_newsletter_citizen_initiatives');
    }
}
