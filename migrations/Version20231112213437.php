<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112213437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E283F8D89D86650F');
        $this->addSql('DROP INDEX IDX_E283F8D89D86650F ON orders');
        $this->addSql('ALTER TABLE orders ADD user_id INT NOT NULL, DROP user_id_id');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E283F8D8A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_E283F8D8A76ED395 ON orders (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Orders DROP FOREIGN KEY FK_E283F8D8A76ED395');
        $this->addSql('DROP INDEX IDX_E283F8D8A76ED395 ON Orders');
        $this->addSql('ALTER TABLE Orders ADD user_id_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE Orders ADD CONSTRAINT FK_E283F8D89D86650F FOREIGN KEY (user_id_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E283F8D89D86650F ON Orders (user_id_id)');
    }
}
