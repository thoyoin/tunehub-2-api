<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260518090936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track ADD playlist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A66BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D6E3F8A66BBD148 ON track (playlist_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A66BBD148');
        $this->addSql('DROP INDEX IDX_D6E3F8A66BBD148 ON track');
        $this->addSql('ALTER TABLE track DROP playlist_id');
    }
}
