<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112193655 extends AbstractMigration
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
        $this->addSql('ALTER TABLE orders DROP user_id_id');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E283F8D8BF396750 FOREIGN KEY (id) REFERENCES users (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Orders DROP FOREIGN KEY FK_E283F8D8BF396750');
        $this->addSql('ALTER TABLE Orders ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE Orders ADD CONSTRAINT FK_E283F8D89D86650F FOREIGN KEY (user_id_id) REFERENCES users (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E283F8D89D86650F ON Orders (user_id_id)');
    }
}
