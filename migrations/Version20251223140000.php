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
        // Créer la table sessions pour PdoSessionHandler (MySQL)
        $this->addSql('
            CREATE TABLE IF NOT EXISTS sessions (
                sess_id VARCHAR(128) NOT NULL PRIMARY KEY,
                sess_data BLOB NOT NULL,
                sess_time INT NOT NULL,
                sess_lifetime INT NOT NULL,
                INDEX sessions_sess_time_idx (sess_time)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ');
    }

    public function down(Schema $schema): void
    {
        // Supprimer la table sessions
        $this->addSql('DROP TABLE IF EXISTS sessions');
    }
}
