<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260512054629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist CHANGE description description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE releases CHANGE cover_url cover_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE slug slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE playlist CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE releases CHANGE cover_url cover_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` CHANGE slug slug VARCHAR(255) DEFAULT NULL');
    }
}
