<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Correction des statuts des tournois futurs qui sont restés à "Terminé"
 */
final class Version20260105133143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Force les statuts à Validé pour tous les tournois futurs (fix bug de statuts)';
    }

    public function up(Schema $schema): void
    {
        // Mettre TOUS les tournois futurs au statut "Validé" (sauf EN_ATTENTE et Refusé)
        // Compatible PostgreSQL et MySQL
        $this->addSql("
            UPDATE tournament
            SET current_status = 'Validé'
            WHERE start_at > NOW()
            AND current_status NOT IN ('En Attente', 'Refusé')
        ");
    }

    public function down(Schema $schema): void
    {
        // On ne peut pas revenir en arrière car on ne sait pas quels étaient les anciens statuts
        $this->addSql('-- Impossible de revenir en arrière');
    }
}
