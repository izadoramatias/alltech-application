<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231126010954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE completed_order (id INT AUTO_INCREMENT NOT NULL, user_order_id INT DEFAULT NULL, delivery_date DATE NOT NULL, UNIQUE INDEX UNIQ_DA226DEA6D128938 (user_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE completed_order ADD CONSTRAINT FK_DA226DEA6D128938 FOREIGN KEY (user_order_id) REFERENCES Orders (id)');
        $this->addSql('ALTER TABLE equipments ADD completed_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipments ADD CONSTRAINT FK_20312694C707C492 FOREIGN KEY (completed_order_id) REFERENCES completed_order (id)');
        $this->addSql('CREATE INDEX IDX_20312694C707C492 ON equipments (completed_order_id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FED90CCA FOREIGN KEY (permission_id) REFERENCES Permissions (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Equipments DROP FOREIGN KEY FK_20312694C707C492');
        $this->addSql('ALTER TABLE completed_order DROP FOREIGN KEY FK_DA226DEA6D128938');
        $this->addSql('DROP TABLE completed_order');
        $this->addSql('DROP INDEX IDX_20312694C707C492 ON Equipments');
        $this->addSql('ALTER TABLE Equipments DROP completed_order_id');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9FED90CCA');
    }
}
