<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260513044712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE library_item (id INT AUTO_INCREMENT NOT NULL, item_type VARCHAR(255) NOT NULL, item_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B9D4EF73A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE library_item ADD CONSTRAINT FK_B9D4EF73A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_item DROP FOREIGN KEY FK_B9D4EF73A76ED395');
        $this->addSql('DROP TABLE library_item');
    }
}
