<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126092025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD id_user_id INT NOT NULL, ADD id_produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF079F37AE5 FOREIGN KEY (id_user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF079F37AE5 ON avis (id_user_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0AABEFE2C ON avis (id_produit_id)');
        $this->addSql('ALTER TABLE commandes ADD id_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C79F37AE5 FOREIGN KEY (id_user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_35D4282C79F37AE5 ON commandes (id_user_id)');
        $this->addSql('ALTER TABLE details_commandes ADD id_commande_id INT NOT NULL, ADD id_produit_id INT NOT NULL');
        $this->addSql('ALTER TABLE details_commandes ADD CONSTRAINT FK_4FD424F79AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE details_commandes ADD CONSTRAINT FK_4FD424F7AABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_4FD424F79AF8E3A3 ON details_commandes (id_commande_id)');
        $this->addSql('CREATE INDEX IDX_4FD424F7AABEFE2C ON details_commandes (id_produit_id)');
        $this->addSql('ALTER TABLE paiements ADD id_commande_id INT NOT NULL');
        $this->addSql('ALTER TABLE paiements ADD CONSTRAINT FK_E1B02E129AF8E3A3 FOREIGN KEY (id_commande_id) REFERENCES commandes (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E1B02E129AF8E3A3 ON paiements (id_commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF079F37AE5');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0AABEFE2C');
        $this->addSql('DROP INDEX IDX_8F91ABF079F37AE5 ON avis');
        $this->addSql('DROP INDEX IDX_8F91ABF0AABEFE2C ON avis');
        $this->addSql('ALTER TABLE avis DROP id_user_id, DROP id_produit_id');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C79F37AE5');
        $this->addSql('DROP INDEX IDX_35D4282C79F37AE5 ON commandes');
        $this->addSql('ALTER TABLE commandes DROP id_user_id');
        $this->addSql('ALTER TABLE details_commandes DROP FOREIGN KEY FK_4FD424F79AF8E3A3');
        $this->addSql('ALTER TABLE details_commandes DROP FOREIGN KEY FK_4FD424F7AABEFE2C');
        $this->addSql('DROP INDEX IDX_4FD424F79AF8E3A3 ON details_commandes');
        $this->addSql('DROP INDEX IDX_4FD424F7AABEFE2C ON details_commandes');
        $this->addSql('ALTER TABLE details_commandes DROP id_commande_id, DROP id_produit_id');
        $this->addSql('ALTER TABLE paiements DROP FOREIGN KEY FK_E1B02E129AF8E3A3');
        $this->addSql('DROP INDEX UNIQ_E1B02E129AF8E3A3 ON paiements');
        $this->addSql('ALTER TABLE paiements DROP id_commande_id');
    }
}
