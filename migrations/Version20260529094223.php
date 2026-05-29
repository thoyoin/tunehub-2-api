<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260529094223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE releases DROP FOREIGN KEY `FK_7896E4D1B7970CF8`');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT FK_7896E4D1B7970CF8 FOREIGN KEY (artist_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE releases DROP FOREIGN KEY FK_7896E4D1B7970CF8');
        $this->addSql('ALTER TABLE releases ADD CONSTRAINT `FK_7896E4D1B7970CF8` FOREIGN KEY (artist_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
