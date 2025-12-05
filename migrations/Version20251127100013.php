<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251127100013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE produits_images (id INT AUTO_INCREMENT NOT NULL, produit_id INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_710EA589F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produits_images ADD CONSTRAINT FK_710EA589F347EFB FOREIGN KEY (produit_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE produits DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_images DROP FOREIGN KEY FK_710EA589F347EFB');
        $this->addSql('DROP TABLE produits_images');
        $this->addSql('ALTER TABLE produits ADD image VARCHAR(255) NOT NULL');
    }
}
