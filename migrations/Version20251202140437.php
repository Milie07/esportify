<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251202140437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Mise à jour colonne tagline + ajout indexes performance sur tournament (current_status, start_at, created_at)';
    }

    public function up(Schema $schema): void
    {
        // Mise à jour tagline (nullable → NOT NULL)
        $this->addSql('ALTER TABLE tournament CHANGE tagline tagline VARCHAR(60) NOT NULL');

        // Ajout d'indexes pour optimiser les performances des requêtes fréquentes
        $this->addSql('CREATE INDEX idx_tournament_current_status ON tournament(current_status)');
        $this->addSql('CREATE INDEX idx_tournament_start_at ON tournament(start_at)');
        $this->addSql('CREATE INDEX idx_tournament_created_at ON tournament(created_at)');

        // Index composite pour les requêtes combinées
        $this->addSql('CREATE INDEX idx_tournament_status_start ON tournament(current_status, start_at)');
    }

    public function down(Schema $schema): void
    {
        // Suppression des indexes
        $this->addSql('DROP INDEX idx_tournament_current_status ON tournament');
        $this->addSql('DROP INDEX idx_tournament_start_at ON tournament');
        $this->addSql('DROP INDEX idx_tournament_created_at ON tournament');
        $this->addSql('DROP INDEX idx_tournament_status_start ON tournament');

        // Retour tagline en nullable
        $this->addSql('ALTER TABLE tournament CHANGE tagline tagline VARCHAR(255) DEFAULT NULL');
    }
}
