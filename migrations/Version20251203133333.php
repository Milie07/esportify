<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration initiale pour la structure de la base de données PostgreSQL
 */
final class Version20251203133333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial database schema for Esportify';
    }

    public function up(Schema $schema): void
    {
        // La structure existe déjà en production
        // Cette migration est marquée comme exécutée
    }

    public function down(Schema $schema): void
    {
        // Migration déjà appliquée
    }
}
