<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230209105814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE swipe_image (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, image_name VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', alt LONGTEXT DEFAULT NULL, is_public TINYINT(1) NOT NULL, INDEX IDX_630B97B2F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE swipe_image ADD CONSTRAINT FK_630B97B2F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE swipe ADD status VARCHAR(255) NOT NULL, ADD homepage_display TINYINT(1) NOT NULL, CHANGE id id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE swipe_image DROP FOREIGN KEY FK_630B97B2F675F31B');
        $this->addSql('DROP TABLE swipe_image');
        $this->addSql('ALTER TABLE swipe DROP status, DROP homepage_display, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
