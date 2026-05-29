<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260529095210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY `FK_D6E3F8A6B12A727D`');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6B12A727D FOREIGN KEY (release_id) REFERENCES releases (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE track DROP FOREIGN KEY FK_D6E3F8A6B12A727D');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT `FK_D6E3F8A6B12A727D` FOREIGN KEY (release_id) REFERENCES releases (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
