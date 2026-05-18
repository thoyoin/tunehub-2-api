<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260515153950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_item ADD playlist_id INT DEFAULT NULL, ADD release_id INT DEFAULT NULL, DROP item_id');
        $this->addSql('ALTER TABLE library_item ADD CONSTRAINT FK_B9D4EF736BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_item ADD CONSTRAINT FK_B9D4EF73B12A727D FOREIGN KEY (release_id) REFERENCES releases (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B9D4EF736BBD148 ON library_item (playlist_id)');
        $this->addSql('CREATE INDEX IDX_B9D4EF73B12A727D ON library_item (release_id)');
        $this->addSql('ALTER TABLE playlist CHANGE item_type item_type VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_item DROP FOREIGN KEY FK_B9D4EF736BBD148');
        $this->addSql('ALTER TABLE library_item DROP FOREIGN KEY FK_B9D4EF73B12A727D');
        $this->addSql('DROP INDEX IDX_B9D4EF736BBD148 ON library_item');
        $this->addSql('DROP INDEX IDX_B9D4EF73B12A727D ON library_item');
        $this->addSql('ALTER TABLE library_item ADD item_id INT NOT NULL, DROP playlist_id, DROP release_id');
        $this->addSql('ALTER TABLE playlist CHANGE item_type item_type VARCHAR(255) DEFAULT \'playlist\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
