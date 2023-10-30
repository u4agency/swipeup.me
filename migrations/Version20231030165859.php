<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030165859 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE newsletter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE pages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE posts_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE urlshortener_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE analytics_visits_swipe (id UUID NOT NULL, swipe_id UUID NOT NULL, user_id VARCHAR(255) NOT NULL, user_agent TEXT NOT NULL, user_ip VARCHAR(255) NOT NULL, visited_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, exited_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BD23883A2DE302F1 ON analytics_visits_swipe (swipe_id)');
        $this->addSql('COMMENT ON COLUMN analytics_visits_swipe.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics_visits_swipe.swipe_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics_visits_swipe.visited_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN analytics_visits_swipe.exited_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE analytics_visits_swipe_up (id UUID NOT NULL, swipeup_id UUID NOT NULL, user_id VARCHAR(255) NOT NULL, user_agent TEXT NOT NULL, user_ip VARCHAR(255) NOT NULL, site_referer TEXT DEFAULT NULL, visited_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E22D4FDAD2CEDEA2 ON analytics_visits_swipe_up (swipeup_id)');
        $this->addSql('COMMENT ON COLUMN analytics_visits_swipe_up.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics_visits_swipe_up.swipeup_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics_visits_swipe_up.visited_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE analytics_visits_urlshortener (id UUID NOT NULL, urlshortener_id INT NOT NULL, user_id VARCHAR(255) NOT NULL, user_agent TEXT NOT NULL, user_ip VARCHAR(255) NOT NULL, site_referer TEXT DEFAULT NULL, visited_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F582396F0B4FD50 ON analytics_visits_urlshortener (urlshortener_id)');
        $this->addSql('COMMENT ON COLUMN analytics_visits_urlshortener.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN analytics_visits_urlshortener.visited_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE newsletter (id INT NOT NULL, email VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, source VARCHAR(255) NOT NULL, points INT NOT NULL, code VARCHAR(13) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7E8585C8E7927C74 ON newsletter (email)');
        $this->addSql('COMMENT ON COLUMN newsletter.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE pages (id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2074E575F675F31B ON pages (author_id)');
        $this->addSql('CREATE TABLE posts (id INT NOT NULL, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_content VARCHAR(255) DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE posts_category (posts_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(posts_id, category_id))');
        $this->addSql('CREATE INDEX IDX_DC83C108D5E258C5 ON posts_category (posts_id)');
        $this->addSql('CREATE INDEX IDX_DC83C10812469DE2 ON posts_category (category_id)');
        $this->addSql('CREATE TABLE posts_user (posts_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(posts_id, user_id))');
        $this->addSql('CREATE INDEX IDX_37C3EFF0D5E258C5 ON posts_user (posts_id)');
        $this->addSql('CREATE INDEX IDX_37C3EFF0A76ED395 ON posts_user (user_id)');
        $this->addSql('CREATE TABLE swipe (id UUID NOT NULL, swipeup_id UUID NOT NULL, background_id UUID NOT NULL, widget_body_id UUID DEFAULT NULL, widget_footer_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sequence INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DB59E9A9D2CEDEA2 ON swipe (swipeup_id)');
        $this->addSql('CREATE INDEX IDX_DB59E9A9C93D69EA ON swipe (background_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB59E9A9954D1A45 ON swipe (widget_body_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB59E9A96897DCC7 ON swipe (widget_footer_id)');
        $this->addSql('COMMENT ON COLUMN swipe.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swipe.swipeup_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swipe.background_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swipe.widget_body_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swipe.widget_footer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swipe.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN swipe.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE swipe_image (id UUID NOT NULL, author_id INT NOT NULL, background_name VARCHAR(255) NOT NULL, uploaded_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, alt TEXT DEFAULT NULL, is_public BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_630B97B2F675F31B ON swipe_image (author_id)');
        $this->addSql('COMMENT ON COLUMN swipe_image.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swipe_image.uploaded_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE swipe_up (id UUID NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(255) NOT NULL, featured_swipe_up BOOLEAN NOT NULL, font VARCHAR(255) DEFAULT NULL, logo_name VARCHAR(255) DEFAULT NULL, icon_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D14E8BD1989D9B62 ON swipe_up (slug)');
        $this->addSql('CREATE INDEX IDX_D14E8BD1F675F31B ON swipe_up (author_id)');
        $this->addSql('COMMENT ON COLUMN swipe_up.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN swipe_up.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN swipe_up.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE urlshortener (id INT NOT NULL, created_by_id INT NOT NULL, link VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFFFC379989D9B62 ON urlshortener (slug)');
        $this->addSql('CREATE INDEX IDX_BFFFC379B03A8386 ON urlshortener (created_by_id)');
        $this->addSql('COMMENT ON COLUMN urlshortener.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, google_id VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, hosted_domain VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE widget (id UUID NOT NULL, created_by_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, price DOUBLE PRECISION DEFAULT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, icon VARCHAR(255) NOT NULL, display JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_85F91ED0B03A8386 ON widget (created_by_id)');
        $this->addSql('COMMENT ON COLUMN widget.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN widget.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE widget_data (id UUID NOT NULL, widget_id UUID NOT NULL, widget_swipe_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data_name VARCHAR(255) NOT NULL, data_value TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3504F8DCFBE885E2 ON widget_data (widget_id)');
        $this->addSql('CREATE INDEX IDX_3504F8DCC18EDFD0 ON widget_data (widget_swipe_id)');
        $this->addSql('COMMENT ON COLUMN widget_data.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget_data.widget_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget_data.widget_swipe_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget_data.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN widget_data.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE widget_swipe (id UUID NOT NULL, widget_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_801FB0BFFBE885E2 ON widget_swipe (widget_id)');
        $this->addSql('COMMENT ON COLUMN widget_swipe.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget_swipe.widget_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget_swipe.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE widget_user (id UUID NOT NULL, widget_id UUID NOT NULL, user_id INT NOT NULL, bought_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1564DDF6FBE885E2 ON widget_user (widget_id)');
        $this->addSql('CREATE INDEX IDX_1564DDF6A76ED395 ON widget_user (user_id)');
        $this->addSql('COMMENT ON COLUMN widget_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget_user.widget_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN widget_user.bought_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE analytics_visits_swipe ADD CONSTRAINT FK_BD23883A2DE302F1 FOREIGN KEY (swipe_id) REFERENCES swipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE analytics_visits_swipe_up ADD CONSTRAINT FK_E22D4FDAD2CEDEA2 FOREIGN KEY (swipeup_id) REFERENCES swipe_up (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE analytics_visits_urlshortener ADD CONSTRAINT FK_F582396F0B4FD50 FOREIGN KEY (urlshortener_id) REFERENCES urlshortener (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pages ADD CONSTRAINT FK_2074E575F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts_category ADD CONSTRAINT FK_DC83C108D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts_category ADD CONSTRAINT FK_DC83C10812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts_user ADD CONSTRAINT FK_37C3EFF0D5E258C5 FOREIGN KEY (posts_id) REFERENCES posts (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE posts_user ADD CONSTRAINT FK_37C3EFF0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9D2CEDEA2 FOREIGN KEY (swipeup_id) REFERENCES swipe_up (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9C93D69EA FOREIGN KEY (background_id) REFERENCES swipe_image (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9954D1A45 FOREIGN KEY (widget_body_id) REFERENCES widget_swipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A96897DCC7 FOREIGN KEY (widget_footer_id) REFERENCES widget_swipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swipe_image ADD CONSTRAINT FK_630B97B2F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE swipe_up ADD CONSTRAINT FK_D14E8BD1F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE urlshortener ADD CONSTRAINT FK_BFFFC379B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE widget ADD CONSTRAINT FK_85F91ED0B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE widget_data ADD CONSTRAINT FK_3504F8DCFBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE widget_data ADD CONSTRAINT FK_3504F8DCC18EDFD0 FOREIGN KEY (widget_swipe_id) REFERENCES widget_swipe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE widget_swipe ADD CONSTRAINT FK_801FB0BFFBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE widget_user ADD CONSTRAINT FK_1564DDF6FBE885E2 FOREIGN KEY (widget_id) REFERENCES widget (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE widget_user ADD CONSTRAINT FK_1564DDF6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE newsletter_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE pages_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE posts_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE urlshortener_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('ALTER TABLE analytics_visits_swipe DROP CONSTRAINT FK_BD23883A2DE302F1');
        $this->addSql('ALTER TABLE analytics_visits_swipe_up DROP CONSTRAINT FK_E22D4FDAD2CEDEA2');
        $this->addSql('ALTER TABLE analytics_visits_urlshortener DROP CONSTRAINT FK_F582396F0B4FD50');
        $this->addSql('ALTER TABLE pages DROP CONSTRAINT FK_2074E575F675F31B');
        $this->addSql('ALTER TABLE posts_category DROP CONSTRAINT FK_DC83C108D5E258C5');
        $this->addSql('ALTER TABLE posts_category DROP CONSTRAINT FK_DC83C10812469DE2');
        $this->addSql('ALTER TABLE posts_user DROP CONSTRAINT FK_37C3EFF0D5E258C5');
        $this->addSql('ALTER TABLE posts_user DROP CONSTRAINT FK_37C3EFF0A76ED395');
        $this->addSql('ALTER TABLE swipe DROP CONSTRAINT FK_DB59E9A9D2CEDEA2');
        $this->addSql('ALTER TABLE swipe DROP CONSTRAINT FK_DB59E9A9C93D69EA');
        $this->addSql('ALTER TABLE swipe DROP CONSTRAINT FK_DB59E9A9954D1A45');
        $this->addSql('ALTER TABLE swipe DROP CONSTRAINT FK_DB59E9A96897DCC7');
        $this->addSql('ALTER TABLE swipe_image DROP CONSTRAINT FK_630B97B2F675F31B');
        $this->addSql('ALTER TABLE swipe_up DROP CONSTRAINT FK_D14E8BD1F675F31B');
        $this->addSql('ALTER TABLE urlshortener DROP CONSTRAINT FK_BFFFC379B03A8386');
        $this->addSql('ALTER TABLE widget DROP CONSTRAINT FK_85F91ED0B03A8386');
        $this->addSql('ALTER TABLE widget_data DROP CONSTRAINT FK_3504F8DCFBE885E2');
        $this->addSql('ALTER TABLE widget_data DROP CONSTRAINT FK_3504F8DCC18EDFD0');
        $this->addSql('ALTER TABLE widget_swipe DROP CONSTRAINT FK_801FB0BFFBE885E2');
        $this->addSql('ALTER TABLE widget_user DROP CONSTRAINT FK_1564DDF6FBE885E2');
        $this->addSql('ALTER TABLE widget_user DROP CONSTRAINT FK_1564DDF6A76ED395');
        $this->addSql('DROP TABLE analytics_visits_swipe');
        $this->addSql('DROP TABLE analytics_visits_swipe_up');
        $this->addSql('DROP TABLE analytics_visits_urlshortener');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE newsletter');
        $this->addSql('DROP TABLE pages');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE posts_category');
        $this->addSql('DROP TABLE posts_user');
        $this->addSql('DROP TABLE swipe');
        $this->addSql('DROP TABLE swipe_image');
        $this->addSql('DROP TABLE swipe_up');
        $this->addSql('DROP TABLE urlshortener');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE widget');
        $this->addSql('DROP TABLE widget_data');
        $this->addSql('DROP TABLE widget_swipe');
        $this->addSql('DROP TABLE widget_user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
