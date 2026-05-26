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
        $invalidRows = $this->connection->fetchAllAssociative(
            "SELECT id, item_id, item_type FROM library_item WHERE item_id IS NULL OR item_type NOT IN ('playlist', 'release')"
        );

        if ($invalidRows !== []) {
            throw new \RuntimeException(sprintf(
                'Cannot migrate library_item: found rows with unsupported item_type values: %s',
                json_encode($invalidRows, JSON_THROW_ON_ERROR)
            ));
        }

        $this->addSql('ALTER TABLE library_item ADD playlist_id INT DEFAULT NULL, ADD release_id INT DEFAULT NULL');
        $this->addSql("UPDATE library_item SET playlist_id = item_id WHERE item_type = 'playlist'");
        $this->addSql("UPDATE library_item SET release_id = item_id WHERE item_type = 'release'");
        $this->addSql('ALTER TABLE library_item ADD CONSTRAINT CHK_library_item_exactly_one_target CHECK ((playlist_id IS NOT NULL AND release_id IS NULL) OR (playlist_id IS NULL AND release_id IS NOT NULL))');
        $this->addSql('ALTER TABLE library_item ADD CONSTRAINT FK_B9D4EF736BBD148 FOREIGN KEY (playlist_id) REFERENCES playlist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE library_item ADD CONSTRAINT FK_B9D4EF73B12A727D FOREIGN KEY (release_id) REFERENCES releases (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_B9D4EF736BBD148 ON library_item (playlist_id)');
        $this->addSql('CREATE INDEX IDX_B9D4EF73B12A727D ON library_item (release_id)');
        $this->addSql('ALTER TABLE library_item DROP item_id');
        $this->addSql('ALTER TABLE playlist CHANGE item_type item_type VARCHAR(255) NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE library_item DROP FOREIGN KEY FK_B9D4EF736BBD148');
        $this->addSql('ALTER TABLE library_item DROP FOREIGN KEY FK_B9D4EF73B12A727D');
        $this->addSql('DROP INDEX IDX_B9D4EF736BBD148 ON library_item');
        $this->addSql('DROP INDEX IDX_B9D4EF73B12A727D ON library_item');
        $this->addSql('ALTER TABLE library_item DROP CHECK CHK_library_item_exactly_one_target');
        $this->addSql('ALTER TABLE library_item ADD item_id INT DEFAULT NULL');
        $this->addSql('UPDATE library_item SET item_id = COALESCE(playlist_id, release_id)');
        $this->addSql('ALTER TABLE library_item CHANGE item_id item_id INT NOT NULL, DROP playlist_id, DROP release_id');
        $this->addSql('ALTER TABLE playlist CHANGE item_type item_type VARCHAR(255) DEFAULT \'playlist\' NOT NULL, CHANGE created_at created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }
}
