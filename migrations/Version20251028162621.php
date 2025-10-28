<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028162621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(20) NOT NULL, complement VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE church (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, name VARCHAR(255) NOT NULL, owner_document_type VARCHAR(4) NOT NULL, owner_document_number VARCHAR(30) NOT NULL, internal_code VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, website VARCHAR(255) DEFAULT NULL, members_limit INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_90CDDD459B95A153 (internal_code), UNIQUE INDEX UNIQ_90CDDD45F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, address_id INT NOT NULL, church_id INT NOT NULL, name VARCHAR(255) NOT NULL, document_type VARCHAR(4) NOT NULL, document_number VARCHAR(30) NOT NULL, birth_date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', email VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_transfer_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', UNIQUE INDEX UNIQ_70E4FA78F5B7AF75 (address_id), INDEX IDX_70E4FA78C1538FD4 (church_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE church ADD CONSTRAINT FK_90CDDD45F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78C1538FD4 FOREIGN KEY (church_id) REFERENCES church (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE church DROP FOREIGN KEY FK_90CDDD45F5B7AF75');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78F5B7AF75');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78C1538FD4');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE church');
        $this->addSql('DROP TABLE member');
    }
}
