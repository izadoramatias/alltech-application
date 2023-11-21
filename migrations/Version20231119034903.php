<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119034903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Donators (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Equipments (id INT AUTO_INCREMENT NOT NULL, donator_id INT NOT NULL, description VARCHAR(45) NOT NULL, receipt_date DATE NOT NULL, availability SMALLINT NOT NULL, INDEX IDX_20312694831BACAF (donator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Equipments ADD CONSTRAINT FK_20312694831BACAF FOREIGN KEY (donator_id) REFERENCES Donators (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Equipments DROP FOREIGN KEY FK_20312694831BACAF');
        $this->addSql('DROP TABLE Donators');
        $this->addSql('DROP TABLE Equipments');
    }
}
