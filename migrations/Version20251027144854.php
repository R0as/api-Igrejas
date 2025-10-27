<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251027144854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, church_id INT NOT NULL, name VARCHAR(255) NOT NULL, document_type VARCHAR(4) NOT NULL, document_number VARCHAR(14) NOT NULL, birth_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', email VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, address_street VARCHAR(255) NOT NULL, address_number VARCHAR(20) NOT NULL, address_complement VARCHAR(255) DEFAULT NULL, address_city VARCHAR(100) NOT NULL, address_state VARCHAR(2) NOT NULL, address_zip_code VARCHAR(9) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_70E4FA78C1538FD4 (church_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78C1538FD4 FOREIGN KEY (church_id) REFERENCES church (id)');
        $this->addSql('ALTER TABLE church CHANGE owner_document_type owner_document_type VARCHAR(4) NOT NULL, CHANGE owner_document_number owner_document_number VARCHAR(14) NOT NULL, CHANGE phone phone VARCHAR(20) NOT NULL, CHANGE address_number address_number VARCHAR(20) NOT NULL, CHANGE address_complement address_complement VARCHAR(255) DEFAULT NULL, CHANGE address_city address_city VARCHAR(100) NOT NULL, CHANGE address_state address_state VARCHAR(2) NOT NULL, CHANGE address_zip_code address_zip_code VARCHAR(9) NOT NULL, CHANGE website website VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90CDDD45B564A605 ON church (owner_document_number)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90CDDD459B95A153 ON church (internal_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78C1538FD4');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP INDEX UNIQ_90CDDD45B564A605 ON church');
        $this->addSql('DROP INDEX UNIQ_90CDDD459B95A153 ON church');
        $this->addSql('ALTER TABLE church CHANGE owner_document_type owner_document_type VARCHAR(255) NOT NULL, CHANGE owner_document_number owner_document_number VARCHAR(255) NOT NULL, CHANGE phone phone VARCHAR(255) NOT NULL, CHANGE address_number address_number VARCHAR(255) NOT NULL, CHANGE address_complement address_complement VARCHAR(255) NOT NULL, CHANGE address_city address_city VARCHAR(255) NOT NULL, CHANGE address_state address_state VARCHAR(255) NOT NULL, CHANGE address_zip_code address_zip_code VARCHAR(255) NOT NULL, CHANGE website website VARCHAR(255) NOT NULL');
    }
}
