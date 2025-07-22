<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722114437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f098d9f6d38');
        $this->addSql('CREATE TABLE orders (id UUID NOT NULL, customer_email VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, total_amount_price_amount NUMERIC(10, 2) NOT NULL, total_amount_price_currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN orders.id IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F098D9F6D38');
        $this->addSql('CREATE TABLE "order" (id UUID NOT NULL, customer_email VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, total_amount_price_amount NUMERIC(10, 2) NOT NULL, total_amount_price_currency VARCHAR(3) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "order".id IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP TABLE orders');
        $this->addSql('ALTER TABLE order_item DROP CONSTRAINT fk_52ea1f098d9f6d38');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT fk_52ea1f098d9f6d38 FOREIGN KEY (order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
