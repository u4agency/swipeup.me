<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508205955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE widget (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, icon VARCHAR(255) NOT NULL, display VARCHAR(255) DEFAULT NULL, INDEX IDX_85F91ED0B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget_data (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_swipe_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3504F8DCFBE885E2 (widget_id), INDEX IDX_3504F8DCC18EDFD0 (widget_swipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget_swipe (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_801FB0BFFBE885E2 (widget_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE widget_user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', widget_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id INT NOT NULL, bought_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1564DDF6FBE885E2 (widget_id), INDEX IDX_1564DDF6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED0B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE widget_data ADD CONSTRAINT FK_3504F8DCFBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id)');
        $this->addSql('ALTER TABLE widget_data ADD CONSTRAINT FK_3504F8DCC18EDFD0 FOREIGN KEY (widget_swipe_id) REFERENCES widget_swipe (id)');
        $this->addSql('ALTER TABLE widget_swipe ADD CONSTRAINT FK_801FB0BFFBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id)');
        $this->addSql('ALTER TABLE widget_user ADD CONSTRAINT FK_1564DDF6FBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id)');
        $this->addSql('ALTER TABLE widget_user ADD CONSTRAINT FK_1564DDF6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE swipe ADD widget_body_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD widget_footer_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9954D1A45 FOREIGN KEY (widget_body_id) REFERENCES widget_swipe (id)');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A96897DCC7 FOREIGN KEY (widget_footer_id) REFERENCES widget_swipe (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB59E9A9954D1A45 ON swipe (widget_body_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB59E9A96897DCC7 ON swipe (widget_footer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A9954D1A45');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A96897DCC7');
        $this->addSql('ALTER TABLE widget DROP FOREIGN KEY FK_85F91ED0B03A8386');
        $this->addSql('ALTER TABLE widget_data DROP FOREIGN KEY FK_3504F8DCFBE885E2');
        $this->addSql('ALTER TABLE widget_data DROP FOREIGN KEY FK_3504F8DCC18EDFD0');
        $this->addSql('ALTER TABLE widget_swipe DROP FOREIGN KEY FK_801FB0BFFBE885E2');
        $this->addSql('ALTER TABLE widget_user DROP FOREIGN KEY FK_1564DDF6FBE885E2');
        $this->addSql('ALTER TABLE widget_user DROP FOREIGN KEY FK_1564DDF6A76ED395');
        $this->addSql('DROP TABLE widget');
        $this->addSql('DROP TABLE widget_data');
        $this->addSql('DROP TABLE widget_swipe');
        $this->addSql('DROP TABLE widget_user');
        $this->addSql('DROP INDEX UNIQ_DB59E9A9954D1A45 ON swipe');
        $this->addSql('DROP INDEX UNIQ_DB59E9A96897DCC7 ON swipe');
        $this->addSql('ALTER TABLE swipe DROP widget_body_id, DROP widget_footer_id');
    }
}
