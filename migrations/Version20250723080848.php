<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723080848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE invoice (id UUID NOT NULL, order_id UUID NOT NULL, generated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, amount_price_amount NUMERIC(10, 2) NOT NULL, amount_price_currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_906517448D9F6D38 ON invoice (order_id)');
        $this->addSql('COMMENT ON COLUMN invoice.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN invoice.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE order_item (id UUID NOT NULL, product_id UUID NOT NULL, order_id UUID NOT NULL, sku VARCHAR(255) NOT NULL, quantity INT NOT NULL, price_price_amount NUMERIC(10, 2) NOT NULL, price_price_currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)');
        $this->addSql('CREATE INDEX IDX_52EA1F098D9F6D38 ON order_item (order_id)');
        $this->addSql('COMMENT ON COLUMN order_item.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_item.product_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN order_item.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE orders (id UUID NOT NULL, customer_email VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, total_amount_price_amount NUMERIC(10, 2) NOT NULL, total_amount_price_currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN orders.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE product (id UUID NOT NULL, name VARCHAR(255) NOT NULL, sku VARCHAR(255) NOT NULL, stock_quantity INT NOT NULL, version INT DEFAULT 1 NOT NULL, price_price_amount NUMERIC(10, 2) NOT NULL, price_price_currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADF9038C4 ON product (sku)');
        $this->addSql('COMMENT ON COLUMN product.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_906517448D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE invoice DROP CONSTRAINT FK_906517448D9F6D38');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('DROP TABLE invoice');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
