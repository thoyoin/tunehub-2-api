<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260529095303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_track DROP FOREIGN KEY `FK_75FFE1E55ED23C43`');
        $this->addSql('ALTER TABLE playlist_track DROP FOREIGN KEY `FK_75FFE1E56BBD148`');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT FK_75FFE1E55ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT FK_75FFE1E56BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist_track DROP FOREIGN KEY FK_75FFE1E56BBD148');
        $this->addSql('ALTER TABLE playlist_track DROP FOREIGN KEY FK_75FFE1E55ED23C43');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT `FK_75FFE1E56BBD148` FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE playlist_track ADD CONSTRAINT `FK_75FFE1E55ED23C43` FOREIGN KEY (track_id) REFERENCES track (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
