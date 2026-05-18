<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260511154334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE playlist (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, visibility VARCHAR(255) NOT NULL, cover_url VARCHAR(255) NOT NULL, owner_id INT NOT NULL, INDEX IDX_D782112D7E3C61F9 (owner_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE releases (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, release_type VARCHAR(255) NOT NULL, cover_url VARCHAR(255) DEFAULT NULL, release_date DATE NOT NULL, status VARCHAR(255) DEFAULT \'pending\' NOT NULL, artist_id INT NOT NULL, INDEX IDX_7896E4D1B7970CF8 (artist_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE track (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, cover_url VARCHAR(255) NOT NULL, duration INT NOT NULL, audio_url VARCHAR(255) NOT NULL, release_date DATE NOT NULL, position INT NOT NULL, artist_id INT NOT NULL, release_id INT NOT NULL, INDEX IDX_D6E3F8A6B7970CF8 (artist_id), INDEX IDX_D6E3F8A6B12A727D (release_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649989D9B62 (slug), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE playlist ADD CONSTRAINT FK_D782112D7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT FK_7896E4D1B7970CF8 FOREIGN KEY (artist_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6B7970CF8 FOREIGN KEY (artist_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6B12A727D FOREIGN KEY (release_id) REFERENCES releases (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist DROP FOREIGN KEY FK_D782112D7E3C61F9');
        $this->addSql('ALTER TABLE releases DROP FOREIGN KEY FK_7896E4D1B7970CF8');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A6B7970CF8');
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A6B12A727D');
        $this->addSql('DROP TABLE playlist');
        $this->addSql('DROP TABLE releases');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE `user`');
    }
}
