<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230818091235 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analytics_visits_swipe (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', swipe_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id VARCHAR(255) NOT NULL, user_agent LONGTEXT NOT NULL, user_ip VARCHAR(255) NOT NULL, visited_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', exited_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BD23883A2DE302F1 (swipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE analytics_visits_swipe_up (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', swipeup_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id VARCHAR(255) NOT NULL, user_agent LONGTEXT NOT NULL, user_ip VARCHAR(255) NOT NULL, site_referer LONGTEXT DEFAULT NULL, visited_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E22D4FDAD2CEDEA2 (swipeup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE analytics_visits_swipe ADD CONSTRAINT FK_BD23883A2DE302F1 FOREIGN KEY (swipe_id) REFERENCES swipe (id)');
        $this->addSql('ALTER TABLE analytics_visits_swipe_up ADD CONSTRAINT FK_E22D4FDAD2CEDEA2 FOREIGN KEY (swipeup_id) REFERENCES swipe_up (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE analytics_visits_swipe DROP FOREIGN KEY FK_BD23883A2DE302F1');
        $this->addSql('ALTER TABLE analytics_visits_swipe_up DROP FOREIGN KEY FK_E22D4FDAD2CEDEA2');
        $this->addSql('DROP TABLE analytics_visits_swipe');
        $this->addSql('DROP TABLE analytics_visits_swipe_up');
    }
}
