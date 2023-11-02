<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107012458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, zip_code VARCHAR(16) NOT NULL, city VARCHAR(20) NOT NULL, district VARCHAR(20) NOT NULL, street VARCHAR(20) NOT NULL, number VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD address_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E283F8D848E1E977 FOREIGN KEY (address_id_id) REFERENCES address (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E283F8D848E1E977 ON orders (address_id_id)');
        $this->addSql('ALTER TABLE permissions CHANGE id id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Orders DROP FOREIGN KEY FK_E283F8D848E1E977');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP INDEX UNIQ_E283F8D848E1E977 ON Orders');
        $this->addSql('ALTER TABLE Orders DROP address_id_id');
        $this->addSql('ALTER TABLE Permissions CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
