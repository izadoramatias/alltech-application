<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231126011329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipments DROP FOREIGN KEY FK_20312694C707C492');
        $this->addSql('CREATE TABLE completed_orders (id INT AUTO_INCREMENT NOT NULL, user_order_id INT DEFAULT NULL, delivery_date DATE NOT NULL, UNIQUE INDEX UNIQ_5B0BE6006D128938 (user_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE completed_orders ADD CONSTRAINT FK_5B0BE6006D128938 FOREIGN KEY (user_order_id) REFERENCES Orders (id)');
        $this->addSql('ALTER TABLE completed_order DROP FOREIGN KEY FK_DA226DEA6D128938');
        $this->addSql('DROP TABLE completed_order');
        $this->addSql('ALTER TABLE equipments DROP FOREIGN KEY FK_20312694C707C492');
        $this->addSql('ALTER TABLE equipments ADD CONSTRAINT FK_20312694C707C492 FOREIGN KEY (completed_order_id) REFERENCES completed_orders (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Equipments DROP FOREIGN KEY FK_20312694C707C492');
        $this->addSql('CREATE TABLE completed_order (id INT AUTO_INCREMENT NOT NULL, user_order_id INT DEFAULT NULL, delivery_date DATE NOT NULL, UNIQUE INDEX UNIQ_DA226DEA6D128938 (user_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE completed_order ADD CONSTRAINT FK_DA226DEA6D128938 FOREIGN KEY (user_order_id) REFERENCES orders (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE completed_orders DROP FOREIGN KEY FK_5B0BE6006D128938');
        $this->addSql('DROP TABLE completed_orders');
        $this->addSql('ALTER TABLE Equipments DROP FOREIGN KEY FK_20312694C707C492');
        $this->addSql('ALTER TABLE Equipments ADD CONSTRAINT FK_20312694C707C492 FOREIGN KEY (completed_order_id) REFERENCES completed_order (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
