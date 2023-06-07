<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607185312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE newsletter (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', source VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7E8585C8E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE swipe (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', swipeup_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', background_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_body_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', widget_footer_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_DB59E9A9D2CEDEA2 (swipeup_id), INDEX IDX_DB59E9A9C93D69EA (background_id), UNIQUE INDEX UNIQ_DB59E9A9954D1A45 (widget_body_id), UNIQUE INDEX UNIQ_DB59E9A96897DCC7 (widget_footer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE swipe_image (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_id INT NOT NULL, background_name VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', alt LONGTEXT DEFAULT NULL, is_public TINYINT(1) NOT NULL, INDEX IDX_630B97B2F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE swipe_up (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, featured_swipe_up TINYINT(1) NOT NULL, font VARCHAR(255) DEFAULT NULL, logo_name VARCHAR(255) NOT NULL, icon_name VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D14E8BD1989D9B62 (slug), INDEX IDX_D14E8BD1F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, username VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, icon VARCHAR(255) NOT NULL, display JSON NOT NULL, INDEX IDX_85F91ED0B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget_data (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_swipe_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', data_name VARCHAR(255) NOT NULL, data_value LONGTEXT NOT NULL, INDEX IDX_3504F8DCFBE885E2 (widget_id), INDEX IDX_3504F8DCC18EDFD0 (widget_swipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget_swipe (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_801FB0BFFBE885E2 (widget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget_user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id INT NOT NULL, bought_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1564DDF6FBE885E2 (widget_id), INDEX IDX_1564DDF6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9D2CEDEA2 FOREIGN KEY (swipeup_id) REFERENCES swipe_up (id)');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9C93D69EA FOREIGN KEY (background_id) REFERENCES swipe_image (id)');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9954D1A45 FOREIGN KEY (widget_body_id) REFERENCES widget_swipe (id)');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A96897DCC7 FOREIGN KEY (widget_footer_id) REFERENCES widget_swipe (id)');
        $this->addSql('ALTER TABLE swipe_image ADD CONSTRAINT FK_630B97B2F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE swipe_up ADD CONSTRAINT FK_D14E8BD1F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE widget_data ADD CONSTRAINT FK_3504F8DCFBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id)');
        $this->addSql('ALTER TABLE widget_data ADD CONSTRAINT FK_3504F8DCC18EDFD0 FOREIGN KEY (widget_swipe_id) REFERENCES widget_swipe (id)');
        $this->addSql('ALTER TABLE widget_swipe ADD CONSTRAINT FK_801FB0BFFBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id)');
        $this->addSql('ALTER TABLE widget_user ADD CONSTRAINT FK_1564DDF6FBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id)');
        $this->addSql('ALTER TABLE widget_user ADD CONSTRAINT FK_1564DDF6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A9D2CEDEA2');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A9C93D69EA');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A9954D1A45');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A96897DCC7');
        $this->addSql('ALTER TABLE swipe_image DROP FOREIGN KEY FK_630B97B2F675F31B');
        $this->addSql('ALTER TABLE swipe_up DROP FOREIGN KEY FK_D14E8BD1F675F31B');
        $this->addSql('ALTER TABLE widget DROP FOREIGN KEY FK_85F91ED0B03A8386');
        $this->addSql('ALTER TABLE widget_data DROP FOREIGN KEY FK_3504F8DCFBE885E2');
        $this->addSql('ALTER TABLE widget_data DROP FOREIGN KEY FK_3504F8DCC18EDFD0');
        $this->addSql('ALTER TABLE widget_swipe DROP FOREIGN KEY FK_801FB0BFFBE885E2');
        $this->addSql('ALTER TABLE widget_user DROP FOREIGN KEY FK_1564DDF6FBE885E2');
        $this->addSql('ALTER TABLE widget_user DROP FOREIGN KEY FK_1564DDF6A76ED395');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE swipe');
        $this->addSql('DROP TABLE swipe_image');
        $this->addSql('DROP TABLE swipe_up');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE widget');
        $this->addSql('DROP TABLE widget_data');
        $this->addSql('DROP TABLE widget_swipe');
        $this->addSql('DROP TABLE widget_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
