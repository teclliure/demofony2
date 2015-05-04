<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150504112103 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE elfinder_file (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGBLOB NOT NULL, size INT NOT NULL, mtime INT NOT NULL, mime VARCHAR(255) NOT NULL, `read` VARCHAR(255) NOT NULL, `write` VARCHAR(255) NOT NULL, locked VARCHAR(255) NOT NULL, hidden VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, INDEX parent_id (parent_id), UNIQUE INDEX parent_name (parent_id, name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_law_transparency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_process_participation_page (id INT AUTO_INCREMENT NOT NULL, process_participation_id INT DEFAULT NULL, citizen_forum_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, position INT NOT NULL, published TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_559D65E59F0F439C (process_participation_id), INDEX IDX_559D65E5730E4076 (citizen_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_image (id INT AUTO_INCREMENT NOT NULL, proposal_id INT DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, INDEX IDX_7AFEA56AF4792058 (proposal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_proposal (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, institutional_answer_id INT DEFAULT NULL, gps_id INT DEFAULT NULL, state INT DEFAULT NULL, moderated TINYINT(1) NOT NULL, user_draft TINYINT(1) NOT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, comments_moderated TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, finished_at DATETIME DEFAULT NULL, automatic_state TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, gallery LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_2834AD14A76ED395 (user_id), UNIQUE INDEX UNIQ_2834AD14AE949E5A (institutional_answer_id), UNIQUE INDEX UNIQ_2834AD14BD6B6DDE (gps_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_proposals_category (proposal_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_F60A70B7F4792058 (proposal_id), INDEX IDX_F60A70B712469DE2 (category_id), PRIMARY KEY(proposal_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_gps (id INT AUTO_INCREMENT NOT NULL, lat DOUBLE PRECISION DEFAULT NULL, lng DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_citizen_initiative (id INT AUTO_INCREMENT NOT NULL, published TINYINT(1) NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, start_at DATETIME NOT NULL, finish_at DATETIME NOT NULL, person VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, gallery LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_process_participation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, institutional_answer_id INT DEFAULT NULL, gps_id INT DEFAULT NULL, debate_at DATETIME NOT NULL, published TINYINT(1) NOT NULL, state INT NOT NULL, info_text LONGTEXT DEFAULT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, comments_moderated TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, finished_at DATETIME DEFAULT NULL, automatic_state TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, gallery LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_30D1F1DDA76ED395 (user_id), UNIQUE INDEX UNIQ_30D1F1DDAE949E5A (institutional_answer_id), UNIQUE INDEX UNIQ_30D1F1DDBD6B6DDE (gps_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_process_participation_category (process_participation_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_F7CBE1479F0F439C (process_participation_id), INDEX IDX_F7CBE14712469DE2 (category_id), PRIMARY KEY(process_participation_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_suggestion (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, subject INT NOT NULL, name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_B12220E3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_citizen_forum (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, institutional_answer_id INT DEFAULT NULL, gps_id INT DEFAULT NULL, published TINYINT(1) NOT NULL, state INT NOT NULL, debate_at DATETIME NOT NULL, info_text LONGTEXT DEFAULT NULL, title VARCHAR(255) NOT NULL, title_slug VARCHAR(255) NOT NULL, comments_moderated TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, finished_at DATETIME DEFAULT NULL, automatic_state TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, gallery LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_DB7BFD82A76ED395 (user_id), UNIQUE INDEX UNIQ_DB7BFD82AE949E5A (institutional_answer_id), UNIQUE INDEX UNIQ_DB7BFD82BD6B6DDE (gps_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_citizen_forums_category (citizen_forum_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_EDC6396A730E4076 (citizen_forum_id), INDEX IDX_EDC6396A12469DE2 (category_id), PRIMARY KEY(citizen_forum_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_comment_vote (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, comment_id INT DEFAULT NULL, value TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_BF99A3F1A76ED395 (user_id), INDEX IDX_BF99A3F1F8697D13 (comment_id), UNIQUE INDEX search_idx (user_id, comment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_proposal_answer (id INT AUTO_INCREMENT NOT NULL, process_participation_id INT DEFAULT NULL, proposal_id INT DEFAULT NULL, citizen_forum_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, position INT NOT NULL, icon INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_24DA8EDC9F0F439C (process_participation_id), INDEX IDX_24DA8EDCF4792058 (proposal_id), INDEX IDX_24DA8EDC730E4076 (citizen_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_proposal_answer_vote (proposal_answer_id INT NOT NULL, vote_id INT NOT NULL, INDEX IDX_3740F76F9F67BBC1 (proposal_answer_id), UNIQUE INDEX UNIQ_3740F76F72DCDAFC (vote_id), PRIMARY KEY(proposal_answer_id, vote_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_category_transparency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, position INT NOT NULL, icon INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_participation_statistics (id INT AUTO_INCREMENT NOT NULL, day DATE NOT NULL, comments INT NOT NULL, votes INT NOT NULL, proposals INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_newsletter (id INT AUTO_INCREMENT NOT NULL, subject VARCHAR(255) NOT NULL, sended TINYINT(1) NOT NULL, sended_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_newsletter_process_participation (newsletter_id INT NOT NULL, process_participation_id INT NOT NULL, INDEX IDX_58F110B722DB1917 (newsletter_id), INDEX IDX_58F110B79F0F439C (process_participation_id), PRIMARY KEY(newsletter_id, process_participation_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_newsletter_proposal (newsletter_id INT NOT NULL, proposal_id INT NOT NULL, INDEX IDX_68594F0322DB1917 (newsletter_id), INDEX IDX_68594F03F4792058 (proposal_id), PRIMARY KEY(newsletter_id, proposal_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_newsletter_document_transparency (newsletter_id INT NOT NULL, document_transparency_id INT NOT NULL, INDEX IDX_F813F6A722DB1917 (newsletter_id), INDEX IDX_F813F6A7C5093772 (document_transparency_id), PRIMARY KEY(newsletter_id, document_transparency_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_document_transparency (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, visits INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_903317CD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_document_transparency_laws (document_transparency_id INT NOT NULL, law_id INT NOT NULL, INDEX IDX_66D11B83C5093772 (document_transparency_id), INDEX IDX_66D11B8354EB478 (law_id), PRIMARY KEY(document_transparency_id, law_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_vote (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_49D0A657A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_comment (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, process_participation_id INT DEFAULT NULL, propsal_id INT DEFAULT NULL, citizen_forum_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, revised TINYINT(1) NOT NULL, moderated TINYINT(1) NOT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, root INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_9794BC67A76ED395 (user_id), INDEX IDX_9794BC679F0F439C (process_participation_id), INDEX IDX_9794BC67EF21118A (propsal_id), INDEX IDX_9794BC67730E4076 (citizen_forum_id), INDEX IDX_9794BC67727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_institutional_answer (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_2E1D786AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_institutional_answer_documents (institutional_answer_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_1AAE40DAAE949E5A (institutional_answer_id), UNIQUE INDEX UNIQ_1AAE40DAC33F7837 (document_id), PRIMARY KEY(institutional_answer_id, document_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_document (id INT AUTO_INCREMENT NOT NULL, process_participation_id INT DEFAULT NULL, proposal_id INT DEFAULT NULL, citizen_initiative_id INT DEFAULT NULL, citizen_forum_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, document_name VARCHAR(255) DEFAULT NULL, INDEX IDX_4FB8B3109F0F439C (process_participation_id), INDEX IDX_4FB8B310F4792058 (proposal_id), INDEX IDX_4FB8B3106FBEFC74 (citizen_initiative_id), INDEX IDX_4FB8B310730E4076 (citizen_forum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_page (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, block_content LONGTEXT DEFAULT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX url_idx (url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_link_transparency (id INT AUTO_INCREMENT NOT NULL, law_id INT DEFAULT NULL, document_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, INDEX IDX_BD6585F154EB478 (law_id), INDEX IDX_BD6585F1C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demofony2_user (id INT AUTO_INCREMENT NOT NULL, gps_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, removed_at DATETIME DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, facebook_access_token VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, google_access_token VARCHAR(255) DEFAULT NULL, twitter_id VARCHAR(255) DEFAULT NULL, twitter_access_token VARCHAR(255) DEFAULT NULL, newsletter_subscribed TINYINT(1) NOT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_9E53F57A92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_9E53F57AA0D96FBF (email_canonical), UNIQUE INDEX UNIQ_9E53F57ABD6B6DDE (gps_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demofony2_process_participation_page ADD CONSTRAINT FK_559D65E59F0F439C FOREIGN KEY (process_participation_id) REFERENCES demofony2_process_participation (id)');
        $this->addSql('ALTER TABLE demofony2_process_participation_page ADD CONSTRAINT FK_559D65E5730E4076 FOREIGN KEY (citizen_forum_id) REFERENCES demofony2_citizen_forum (id)');
        $this->addSql('ALTER TABLE demofony2_image ADD CONSTRAINT FK_7AFEA56AF4792058 FOREIGN KEY (proposal_id) REFERENCES demofony2_citizen_initiative (id)');
        $this->addSql('ALTER TABLE demofony2_proposal ADD CONSTRAINT FK_2834AD14A76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_proposal ADD CONSTRAINT FK_2834AD14AE949E5A FOREIGN KEY (institutional_answer_id) REFERENCES demofony2_institutional_answer (id)');
        $this->addSql('ALTER TABLE demofony2_proposal ADD CONSTRAINT FK_2834AD14BD6B6DDE FOREIGN KEY (gps_id) REFERENCES demofony2_gps (id)');
        $this->addSql('ALTER TABLE demofony2_proposals_category ADD CONSTRAINT FK_F60A70B7F4792058 FOREIGN KEY (proposal_id) REFERENCES demofony2_proposal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_proposals_category ADD CONSTRAINT FK_F60A70B712469DE2 FOREIGN KEY (category_id) REFERENCES demofony2_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_process_participation ADD CONSTRAINT FK_30D1F1DDA76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_process_participation ADD CONSTRAINT FK_30D1F1DDAE949E5A FOREIGN KEY (institutional_answer_id) REFERENCES demofony2_institutional_answer (id)');
        $this->addSql('ALTER TABLE demofony2_process_participation ADD CONSTRAINT FK_30D1F1DDBD6B6DDE FOREIGN KEY (gps_id) REFERENCES demofony2_gps (id)');
        $this->addSql('ALTER TABLE demofony2_process_participation_category ADD CONSTRAINT FK_F7CBE1479F0F439C FOREIGN KEY (process_participation_id) REFERENCES demofony2_process_participation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_process_participation_category ADD CONSTRAINT FK_F7CBE14712469DE2 FOREIGN KEY (category_id) REFERENCES demofony2_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_suggestion ADD CONSTRAINT FK_B12220E3A76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_citizen_forum ADD CONSTRAINT FK_DB7BFD82A76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_citizen_forum ADD CONSTRAINT FK_DB7BFD82AE949E5A FOREIGN KEY (institutional_answer_id) REFERENCES demofony2_institutional_answer (id)');
        $this->addSql('ALTER TABLE demofony2_citizen_forum ADD CONSTRAINT FK_DB7BFD82BD6B6DDE FOREIGN KEY (gps_id) REFERENCES demofony2_gps (id)');
        $this->addSql('ALTER TABLE demofony2_citizen_forums_category ADD CONSTRAINT FK_EDC6396A730E4076 FOREIGN KEY (citizen_forum_id) REFERENCES demofony2_citizen_forum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_citizen_forums_category ADD CONSTRAINT FK_EDC6396A12469DE2 FOREIGN KEY (category_id) REFERENCES demofony2_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_comment_vote ADD CONSTRAINT FK_BF99A3F1A76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_comment_vote ADD CONSTRAINT FK_BF99A3F1F8697D13 FOREIGN KEY (comment_id) REFERENCES demofony2_comment (id)');
        $this->addSql('ALTER TABLE demofony2_proposal_answer ADD CONSTRAINT FK_24DA8EDC9F0F439C FOREIGN KEY (process_participation_id) REFERENCES demofony2_process_participation (id)');
        $this->addSql('ALTER TABLE demofony2_proposal_answer ADD CONSTRAINT FK_24DA8EDCF4792058 FOREIGN KEY (proposal_id) REFERENCES demofony2_proposal (id)');
        $this->addSql('ALTER TABLE demofony2_proposal_answer ADD CONSTRAINT FK_24DA8EDC730E4076 FOREIGN KEY (citizen_forum_id) REFERENCES demofony2_citizen_forum (id)');
        $this->addSql('ALTER TABLE demofony2_proposal_answer_vote ADD CONSTRAINT FK_3740F76F9F67BBC1 FOREIGN KEY (proposal_answer_id) REFERENCES demofony2_proposal_answer (id)');
        $this->addSql('ALTER TABLE demofony2_proposal_answer_vote ADD CONSTRAINT FK_3740F76F72DCDAFC FOREIGN KEY (vote_id) REFERENCES demofony2_vote (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_process_participation ADD CONSTRAINT FK_58F110B722DB1917 FOREIGN KEY (newsletter_id) REFERENCES demofony2_newsletter (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_process_participation ADD CONSTRAINT FK_58F110B79F0F439C FOREIGN KEY (process_participation_id) REFERENCES demofony2_process_participation (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_proposal ADD CONSTRAINT FK_68594F0322DB1917 FOREIGN KEY (newsletter_id) REFERENCES demofony2_newsletter (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_proposal ADD CONSTRAINT FK_68594F03F4792058 FOREIGN KEY (proposal_id) REFERENCES demofony2_proposal (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_document_transparency ADD CONSTRAINT FK_F813F6A722DB1917 FOREIGN KEY (newsletter_id) REFERENCES demofony2_newsletter (id)');
        $this->addSql('ALTER TABLE demofony2_newsletter_document_transparency ADD CONSTRAINT FK_F813F6A7C5093772 FOREIGN KEY (document_transparency_id) REFERENCES demofony2_document_transparency (id)');
        $this->addSql('ALTER TABLE demofony2_document_transparency ADD CONSTRAINT FK_903317CD12469DE2 FOREIGN KEY (category_id) REFERENCES demofony2_category_transparency (id)');
        $this->addSql('ALTER TABLE demofony2_document_transparency_laws ADD CONSTRAINT FK_66D11B83C5093772 FOREIGN KEY (document_transparency_id) REFERENCES demofony2_document_transparency (id)');
        $this->addSql('ALTER TABLE demofony2_document_transparency_laws ADD CONSTRAINT FK_66D11B8354EB478 FOREIGN KEY (law_id) REFERENCES demofony2_law_transparency (id)');
        $this->addSql('ALTER TABLE demofony2_vote ADD CONSTRAINT FK_49D0A657A76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_comment ADD CONSTRAINT FK_9794BC67A76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_comment ADD CONSTRAINT FK_9794BC679F0F439C FOREIGN KEY (process_participation_id) REFERENCES demofony2_process_participation (id)');
        $this->addSql('ALTER TABLE demofony2_comment ADD CONSTRAINT FK_9794BC67EF21118A FOREIGN KEY (propsal_id) REFERENCES demofony2_proposal (id)');
        $this->addSql('ALTER TABLE demofony2_comment ADD CONSTRAINT FK_9794BC67730E4076 FOREIGN KEY (citizen_forum_id) REFERENCES demofony2_citizen_forum (id)');
        $this->addSql('ALTER TABLE demofony2_comment ADD CONSTRAINT FK_9794BC67727ACA70 FOREIGN KEY (parent_id) REFERENCES demofony2_comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_institutional_answer ADD CONSTRAINT FK_2E1D786AA76ED395 FOREIGN KEY (user_id) REFERENCES demofony2_user (id)');
        $this->addSql('ALTER TABLE demofony2_institutional_answer_documents ADD CONSTRAINT FK_1AAE40DAAE949E5A FOREIGN KEY (institutional_answer_id) REFERENCES demofony2_institutional_answer (id)');
        $this->addSql('ALTER TABLE demofony2_institutional_answer_documents ADD CONSTRAINT FK_1AAE40DAC33F7837 FOREIGN KEY (document_id) REFERENCES demofony2_document (id)');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B3109F0F439C FOREIGN KEY (process_participation_id) REFERENCES demofony2_process_participation (id)');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B310F4792058 FOREIGN KEY (proposal_id) REFERENCES demofony2_proposal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B3106FBEFC74 FOREIGN KEY (citizen_initiative_id) REFERENCES demofony2_citizen_initiative (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_document ADD CONSTRAINT FK_4FB8B310730E4076 FOREIGN KEY (citizen_forum_id) REFERENCES demofony2_citizen_forum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE demofony2_link_transparency ADD CONSTRAINT FK_BD6585F154EB478 FOREIGN KEY (law_id) REFERENCES demofony2_law_transparency (id)');
        $this->addSql('ALTER TABLE demofony2_link_transparency ADD CONSTRAINT FK_BD6585F1C33F7837 FOREIGN KEY (document_id) REFERENCES demofony2_document_transparency (id)');
        $this->addSql('ALTER TABLE demofony2_user ADD CONSTRAINT FK_9E53F57ABD6B6DDE FOREIGN KEY (gps_id) REFERENCES demofony2_gps (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE demofony2_document_transparency_laws DROP FOREIGN KEY FK_66D11B8354EB478');
        $this->addSql('ALTER TABLE demofony2_link_transparency DROP FOREIGN KEY FK_BD6585F154EB478');
        $this->addSql('ALTER TABLE demofony2_proposals_category DROP FOREIGN KEY FK_F60A70B7F4792058');
        $this->addSql('ALTER TABLE demofony2_proposal_answer DROP FOREIGN KEY FK_24DA8EDCF4792058');
        $this->addSql('ALTER TABLE demofony2_newsletter_proposal DROP FOREIGN KEY FK_68594F03F4792058');
        $this->addSql('ALTER TABLE demofony2_comment DROP FOREIGN KEY FK_9794BC67EF21118A');
        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B310F4792058');
        $this->addSql('ALTER TABLE demofony2_proposal DROP FOREIGN KEY FK_2834AD14BD6B6DDE');
        $this->addSql('ALTER TABLE demofony2_process_participation DROP FOREIGN KEY FK_30D1F1DDBD6B6DDE');
        $this->addSql('ALTER TABLE demofony2_citizen_forum DROP FOREIGN KEY FK_DB7BFD82BD6B6DDE');
        $this->addSql('ALTER TABLE demofony2_user DROP FOREIGN KEY FK_9E53F57ABD6B6DDE');
        $this->addSql('ALTER TABLE demofony2_image DROP FOREIGN KEY FK_7AFEA56AF4792058');
        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B3106FBEFC74');
        $this->addSql('ALTER TABLE demofony2_process_participation_page DROP FOREIGN KEY FK_559D65E59F0F439C');
        $this->addSql('ALTER TABLE demofony2_process_participation_category DROP FOREIGN KEY FK_F7CBE1479F0F439C');
        $this->addSql('ALTER TABLE demofony2_proposal_answer DROP FOREIGN KEY FK_24DA8EDC9F0F439C');
        $this->addSql('ALTER TABLE demofony2_newsletter_process_participation DROP FOREIGN KEY FK_58F110B79F0F439C');
        $this->addSql('ALTER TABLE demofony2_comment DROP FOREIGN KEY FK_9794BC679F0F439C');
        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B3109F0F439C');
        $this->addSql('ALTER TABLE demofony2_process_participation_page DROP FOREIGN KEY FK_559D65E5730E4076');
        $this->addSql('ALTER TABLE demofony2_citizen_forums_category DROP FOREIGN KEY FK_EDC6396A730E4076');
        $this->addSql('ALTER TABLE demofony2_proposal_answer DROP FOREIGN KEY FK_24DA8EDC730E4076');
        $this->addSql('ALTER TABLE demofony2_comment DROP FOREIGN KEY FK_9794BC67730E4076');
        $this->addSql('ALTER TABLE demofony2_document DROP FOREIGN KEY FK_4FB8B310730E4076');
        $this->addSql('ALTER TABLE demofony2_proposal_answer_vote DROP FOREIGN KEY FK_3740F76F9F67BBC1');
        $this->addSql('ALTER TABLE demofony2_document_transparency DROP FOREIGN KEY FK_903317CD12469DE2');
        $this->addSql('ALTER TABLE demofony2_newsletter_process_participation DROP FOREIGN KEY FK_58F110B722DB1917');
        $this->addSql('ALTER TABLE demofony2_newsletter_proposal DROP FOREIGN KEY FK_68594F0322DB1917');
        $this->addSql('ALTER TABLE demofony2_newsletter_document_transparency DROP FOREIGN KEY FK_F813F6A722DB1917');
        $this->addSql('ALTER TABLE demofony2_newsletter_document_transparency DROP FOREIGN KEY FK_F813F6A7C5093772');
        $this->addSql('ALTER TABLE demofony2_document_transparency_laws DROP FOREIGN KEY FK_66D11B83C5093772');
        $this->addSql('ALTER TABLE demofony2_link_transparency DROP FOREIGN KEY FK_BD6585F1C33F7837');
        $this->addSql('ALTER TABLE demofony2_proposal_answer_vote DROP FOREIGN KEY FK_3740F76F72DCDAFC');
        $this->addSql('ALTER TABLE demofony2_comment_vote DROP FOREIGN KEY FK_BF99A3F1F8697D13');
        $this->addSql('ALTER TABLE demofony2_comment DROP FOREIGN KEY FK_9794BC67727ACA70');
        $this->addSql('ALTER TABLE demofony2_proposals_category DROP FOREIGN KEY FK_F60A70B712469DE2');
        $this->addSql('ALTER TABLE demofony2_process_participation_category DROP FOREIGN KEY FK_F7CBE14712469DE2');
        $this->addSql('ALTER TABLE demofony2_citizen_forums_category DROP FOREIGN KEY FK_EDC6396A12469DE2');
        $this->addSql('ALTER TABLE demofony2_proposal DROP FOREIGN KEY FK_2834AD14AE949E5A');
        $this->addSql('ALTER TABLE demofony2_process_participation DROP FOREIGN KEY FK_30D1F1DDAE949E5A');
        $this->addSql('ALTER TABLE demofony2_citizen_forum DROP FOREIGN KEY FK_DB7BFD82AE949E5A');
        $this->addSql('ALTER TABLE demofony2_institutional_answer_documents DROP FOREIGN KEY FK_1AAE40DAAE949E5A');
        $this->addSql('ALTER TABLE demofony2_institutional_answer_documents DROP FOREIGN KEY FK_1AAE40DAC33F7837');
        $this->addSql('ALTER TABLE demofony2_proposal DROP FOREIGN KEY FK_2834AD14A76ED395');
        $this->addSql('ALTER TABLE demofony2_process_participation DROP FOREIGN KEY FK_30D1F1DDA76ED395');
        $this->addSql('ALTER TABLE demofony2_suggestion DROP FOREIGN KEY FK_B12220E3A76ED395');
        $this->addSql('ALTER TABLE demofony2_citizen_forum DROP FOREIGN KEY FK_DB7BFD82A76ED395');
        $this->addSql('ALTER TABLE demofony2_comment_vote DROP FOREIGN KEY FK_BF99A3F1A76ED395');
        $this->addSql('ALTER TABLE demofony2_vote DROP FOREIGN KEY FK_49D0A657A76ED395');
        $this->addSql('ALTER TABLE demofony2_comment DROP FOREIGN KEY FK_9794BC67A76ED395');
        $this->addSql('ALTER TABLE demofony2_institutional_answer DROP FOREIGN KEY FK_2E1D786AA76ED395');
        $this->addSql('DROP TABLE elfinder_file');
        $this->addSql('DROP TABLE demofony2_law_transparency');
        $this->addSql('DROP TABLE demofony2_process_participation_page');
        $this->addSql('DROP TABLE demofony2_image');
        $this->addSql('DROP TABLE demofony2_proposal');
        $this->addSql('DROP TABLE demofony2_proposals_category');
        $this->addSql('DROP TABLE demofony2_gps');
        $this->addSql('DROP TABLE demofony2_citizen_initiative');
        $this->addSql('DROP TABLE demofony2_process_participation');
        $this->addSql('DROP TABLE demofony2_process_participation_category');
        $this->addSql('DROP TABLE demofony2_suggestion');
        $this->addSql('DROP TABLE demofony2_citizen_forum');
        $this->addSql('DROP TABLE demofony2_citizen_forums_category');
        $this->addSql('DROP TABLE demofony2_comment_vote');
        $this->addSql('DROP TABLE demofony2_proposal_answer');
        $this->addSql('DROP TABLE demofony2_proposal_answer_vote');
        $this->addSql('DROP TABLE demofony2_category_transparency');
        $this->addSql('DROP TABLE demofony2_participation_statistics');
        $this->addSql('DROP TABLE demofony2_newsletter');
        $this->addSql('DROP TABLE demofony2_newsletter_process_participation');
        $this->addSql('DROP TABLE demofony2_newsletter_proposal');
        $this->addSql('DROP TABLE demofony2_newsletter_document_transparency');
        $this->addSql('DROP TABLE demofony2_document_transparency');
        $this->addSql('DROP TABLE demofony2_document_transparency_laws');
        $this->addSql('DROP TABLE demofony2_vote');
        $this->addSql('DROP TABLE demofony2_comment');
        $this->addSql('DROP TABLE demofony2_category');
        $this->addSql('DROP TABLE demofony2_institutional_answer');
        $this->addSql('DROP TABLE demofony2_institutional_answer_documents');
        $this->addSql('DROP TABLE demofony2_document');
        $this->addSql('DROP TABLE demofony2_page');
        $this->addSql('DROP TABLE demofony2_link_transparency');
        $this->addSql('DROP TABLE demofony2_user');
    }
}
