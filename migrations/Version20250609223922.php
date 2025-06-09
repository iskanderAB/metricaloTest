<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250609223922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE card (id BINARY(16) NOT NULL, card_number VARCHAR(255) NOT NULL, expiry_month INT NOT NULL, expiry_year INT NOT NULL, cvv INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE payment (id BINARY(16) NOT NULL, amount INT NOT NULL, currency VARCHAR(3) NOT NULL, transaction_id VARCHAR(255) NOT NULL, created_date DATETIME NOT NULL, card_id BINARY(16) DEFAULT NULL, INDEX IDX_6D28840D4ACC9A20 (card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE payment ADD CONSTRAINT FK_6D28840D4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D4ACC9A20
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE card
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE payment
        SQL);
    }
}
