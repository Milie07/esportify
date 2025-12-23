<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour créer la table de sessions
 */
final class Version20251223140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Création de la table sessions pour stocker les sessions en base de données';
    }

    public function up(Schema $schema): void
    {
        // Créer la table sessions pour PdoSessionHandler (PostgreSQL)
        $this->addSql('
            CREATE TABLE IF NOT EXISTS sessions (
                sess_id VARCHAR(128) NOT NULL PRIMARY KEY,
                sess_data BYTEA NOT NULL,
                sess_time INTEGER NOT NULL,
                sess_lifetime INTEGER NOT NULL
            )
        ');
        $this->addSql('CREATE INDEX IF NOT EXISTS sessions_sess_time_idx ON sessions (sess_time)');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la table sessions
        $this->addSql('DROP TABLE IF EXISTS sessions');
    }
}
