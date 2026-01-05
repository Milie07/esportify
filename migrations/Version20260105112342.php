<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105112342 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Mise à jour des dates des tournois 2026 et ajout du tournoi L\'Overload';
    }

    public function up(Schema $schema): void
    {
        // Ajouter l'image du nouveau tournoi "L'Overload" si elle n'existe pas déjà
        $this->addSql("
            INSERT INTO tournament_images (image_url, code)
            SELECT 'uploads/tournaments/loverload.jpg', 10
            WHERE NOT EXISTS (SELECT 1 FROM tournament_images WHERE code = 10)
        ");

        // Mettre à jour les dates des tournois existants
        $this->addSql("UPDATE tournament SET start_at = '2026-01-06 10:00:00', end_at = '2026-01-06 20:00:00' WHERE title = 'Iron Arena'");
        $this->addSql("UPDATE tournament SET start_at = '2026-01-15 10:00:00', end_at = '2026-01-16 10:00:00' WHERE title = 'Neon Rift'");
        $this->addSql("UPDATE tournament SET start_at = '2026-01-20 10:00:00', end_at = '2026-01-22 10:00:00' WHERE title = 'Pixel Panic'");
        $this->addSql("UPDATE tournament SET start_at = '2026-01-25 10:00:00', end_at = '2026-01-27 10:00:00' WHERE title = 'Noob Games'");
        $this->addSql("UPDATE tournament SET start_at = '2026-02-01 10:00:00', end_at = '2026-02-02 10:00:00' WHERE title = 'Eclipse Masters'");
        $this->addSql("UPDATE tournament SET start_at = '2026-02-15 10:00:00', end_at = '2026-02-16 20:00:00' WHERE title = 'Quantum Bash'");
        $this->addSql("UPDATE tournament SET start_at = '2026-02-20 10:00:00', end_at = '2026-02-20 20:00:00' WHERE title = 'Pixel Warzone'");
        $this->addSql("UPDATE tournament SET start_at = '2026-02-25 10:00:00', end_at = '2026-02-26 20:00:00' WHERE title = 'Next Level Cup'");
        $this->addSql("UPDATE tournament SET start_at = '2026-03-02 12:00:00', end_at = '2026-03-03 18:00:00' WHERE title = 'Dernier Combo'");

        // Ajouter le nouveau tournoi "L'Overload" si il n'existe pas déjà
        $this->addSql("
            INSERT INTO tournament (
                tournament_image_id,
                member_id,
                title,
                description,
                start_at,
                end_at,
                capacity_gauge,
                tagline,
                created_at,
                current_status
            )
            SELECT
                (SELECT tournament_image_id FROM tournament_images WHERE code = 10),
                (SELECT member_id FROM member WHERE pseudo = 'HugoOrga'),
                'L''Overload',
                'Jeux de briques en solo. Dans ce tournoi dédié aux fans de Tétris, tout se joue dans l''ultime combo. Ultra technique, ultra nerveux, Dernier Combo met en scène les meilleurs fighters dans des duels millimétrés. Ici, une erreur, c''est le chaos. Une réussite, c''est l''extase.',
                '2026-03-10 12:00:00',
                '2026-03-11 18:00:00',
                90,
                'Une seule brique peut tout changer.',
                '2025-01-02 10:00:00',
                'Validé'
            WHERE NOT EXISTS (SELECT 1 FROM tournament WHERE title = 'L''Overload')
        ");
    }

    public function down(Schema $schema): void
    {
        // Supprimer le tournoi "L'Overload"
        $this->addSql("DELETE FROM tournament WHERE title = 'L''Overload'");

        // Supprimer l'image du tournoi
        $this->addSql("DELETE FROM tournament_images WHERE code = 10");

        // Remettre les anciennes dates (optionnel - vous pouvez laisser vide si vous ne voulez pas revenir en arrière)
        $this->addSql("UPDATE tournament SET start_at = '2025-11-12 10:00:00', end_at = '2025-11-14 10:00:00' WHERE title = 'Iron Arena'");
        $this->addSql("UPDATE tournament SET start_at = '2025-11-25 10:00:00', end_at = '2025-11-26 10:00:00' WHERE title = 'Neon Rift'");
        $this->addSql("UPDATE tournament SET start_at = '2025-11-30 10:00:00', end_at = '2025-12-01 10:00:00' WHERE title = 'Pixel Panic'");
        $this->addSql("UPDATE tournament SET start_at = '2025-12-10 10:00:00', end_at = '2025-12-15 10:00:00' WHERE title = 'Noob Games'");
        $this->addSql("UPDATE tournament SET start_at = '2025-12-16 10:00:00', end_at = '2025-12-17 10:00:00' WHERE title = 'Eclipse Masters'");
        $this->addSql("UPDATE tournament SET start_at = '2025-12-18 10:00:00', end_at = '2025-12-18 20:00:00' WHERE title = 'Quantum Bash'");
        $this->addSql("UPDATE tournament SET start_at = '2025-12-27 10:00:00', end_at = '2025-12-28 20:00:00' WHERE title = 'Pixel Warzone'");
        $this->addSql("UPDATE tournament SET start_at = '2026-01-04 10:00:00', end_at = '2026-01-04 20:00:00' WHERE title = 'Next Level Cup'");
        $this->addSql("UPDATE tournament SET start_at = '2026-01-10 12:00:00', end_at = '2026-01-11 18:00:00' WHERE title = 'Dernier Combo'");
    }
}
