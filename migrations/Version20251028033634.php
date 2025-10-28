<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028033634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, street VARCHAR(255) NOT NULL, number VARCHAR(20) NOT NULL, complement VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, zip_code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE church ADD address_id INT NOT NULL, DROP address_street, DROP address_number, DROP address_complement, DROP address_city, DROP address_state, DROP address_zip_code');
        $this->addSql('ALTER TABLE church ADD CONSTRAINT FK_90CDDD45F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90CDDD45F5B7AF75 ON church (address_id)');
        $this->addSql('ALTER TABLE member ADD address_id INT NOT NULL, DROP address_street, DROP address_number, DROP address_complement, DROP address_city, DROP address_state, DROP address_zip_code');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_70E4FA78F5B7AF75 ON member (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE church DROP FOREIGN KEY FK_90CDDD45F5B7AF75');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78F5B7AF75');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP INDEX UNIQ_90CDDD45F5B7AF75 ON church');
        $this->addSql('ALTER TABLE church ADD address_street VARCHAR(255) NOT NULL, ADD address_number VARCHAR(20) NOT NULL, ADD address_complement VARCHAR(255) DEFAULT NULL, ADD address_city VARCHAR(100) NOT NULL, ADD address_state VARCHAR(2) NOT NULL, ADD address_zip_code VARCHAR(9) NOT NULL, DROP address_id');
        $this->addSql('DROP INDEX UNIQ_70E4FA78F5B7AF75 ON member');
        $this->addSql('ALTER TABLE member ADD address_street VARCHAR(255) NOT NULL, ADD address_number VARCHAR(20) NOT NULL, ADD address_complement VARCHAR(255) DEFAULT NULL, ADD address_city VARCHAR(100) NOT NULL, ADD address_state VARCHAR(2) NOT NULL, ADD address_zip_code VARCHAR(9) NOT NULL, DROP address_id');
    }
}
