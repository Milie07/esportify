<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration initiale pour la structure de la base de données MySQL
 */
final class Version20251203133333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial database schema for Esportify with all tables';
    }

    public function up(Schema $schema): void
    {
        // Tables principales
        $this->addSql('CREATE TABLE tournament_images (tournament_image_id INT UNSIGNED AUTO_INCREMENT NOT NULL, image_url VARCHAR(255) NOT NULL, code INT NOT NULL, UNIQUE INDEX UNIQ_84B5403A77153098 (code), PRIMARY KEY(tournament_image_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_avatars (member_avatar_id INT UNSIGNED AUTO_INCREMENT NOT NULL, avatar_url VARCHAR(255) DEFAULT NULL, code INT NOT NULL, UNIQUE INDEX UNIQ_F3D32B477153098 (code), PRIMARY KEY(member_avatar_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_roles (member_role_id INT UNSIGNED AUTO_INCREMENT NOT NULL, member_role_label VARCHAR(255) DEFAULT \'Player\' NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, code VARCHAR(32) NOT NULL, UNIQUE INDEX uq_member_roles_code (code), PRIMARY KEY(member_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE `member` (member_id INT UNSIGNED AUTO_INCREMENT NOT NULL, member_avatar_id INT UNSIGNED DEFAULT NULL, member_role_id INT UNSIGNED NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, pseudo VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, password_hash VARCHAR(255) NOT NULL, member_score INT DEFAULT 0 NOT NULL, INDEX IDX_70E4FA78C610EDD5 (member_avatar_id), INDEX IDX_70E4FA7869F79538 (member_role_id), UNIQUE INDEX uq_member_pseudo (pseudo), UNIQUE INDEX uq_member_email (email), PRIMARY KEY(member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE tournament (tournament_id INT UNSIGNED AUTO_INCREMENT NOT NULL, tournament_image_id INT UNSIGNED DEFAULT NULL, member_id INT UNSIGNED NOT NULL, title VARCHAR(250) NOT NULL, description LONGTEXT NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', capacity_gauge INT DEFAULT 0 NOT NULL, tagline VARCHAR(60) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', current_status VARCHAR(20) DEFAULT \'En Attente\' NOT NULL, INDEX IDX_BD5FB8D9DD1BC388 (tournament_image_id), INDEX IDX_BD5FB8D97597D3FE (member_id), PRIMARY KEY(tournament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Tables de liaison
        $this->addSql('CREATE TABLE member_participate_tournament (member_id INT UNSIGNED NOT NULL, tournament_id INT UNSIGNED NOT NULL, tournament_score INT DEFAULT 0 NOT NULL, INDEX IDX_89D6C1397597D3FE (member_id), INDEX IDX_89D6C13933D1A3E7 (tournament_id), PRIMARY KEY(member_id, tournament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_register_tournament (member_id INT UNSIGNED NOT NULL, tournament_id INT UNSIGNED NOT NULL, date_register DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_7FC19D2F7597D3FE (member_id), INDEX IDX_7FC19D2F33D1A3E7 (tournament_id), PRIMARY KEY(member_id, tournament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_add_favorites_tournament (member_id INT UNSIGNED NOT NULL, tournament_id INT UNSIGNED NOT NULL, INDEX IDX_2D4A983E7597D3FE (member_id), INDEX IDX_2D4A983E33D1A3E7 (tournament_id), PRIMARY KEY(member_id, tournament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member_moderate_roles (member_id INT UNSIGNED NOT NULL, member_role_id INT UNSIGNED NOT NULL, member_label_status VARCHAR(20) DEFAULT \'Actif\' NOT NULL, assigned_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E8D162F27597D3FE (member_id), INDEX IDX_E8D162F269F79538 (member_role_id), PRIMARY KEY(member_id, member_role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Historique et système
        $this->addSql('CREATE TABLE tournament_history (tournament_history_id INT UNSIGNED AUTO_INCREMENT NOT NULL, tournament_id INT UNSIGNED NOT NULL, member_id INT UNSIGNED NOT NULL, updated_at DATETIME DEFAULT NULL, action_type VARCHAR(50) NOT NULL, detail JSON DEFAULT NULL, to_status VARCHAR(20) NOT NULL, INDEX IDX_4CB58B4133D1A3E7 (tournament_id), INDEX IDX_4CB58B417597D3FE (member_id), PRIMARY KEY(tournament_history_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Clés étrangères
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA78C610EDD5 FOREIGN KEY (member_avatar_id) REFERENCES member_avatars (member_avatar_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE `member` ADD CONSTRAINT FK_70E4FA7869F79538 FOREIGN KEY (member_role_id) REFERENCES member_roles (member_role_id)');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D9DD1BC388 FOREIGN KEY (tournament_image_id) REFERENCES tournament_images (tournament_image_id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D97597D3FE FOREIGN KEY (member_id) REFERENCES `member` (member_id)');
        $this->addSql('ALTER TABLE member_participate_tournament ADD CONSTRAINT FK_89D6C1397597D3FE FOREIGN KEY (member_id) REFERENCES `member` (member_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_participate_tournament ADD CONSTRAINT FK_89D6C13933D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (tournament_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_register_tournament ADD CONSTRAINT FK_7FC19D2F7597D3FE FOREIGN KEY (member_id) REFERENCES `member` (member_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_register_tournament ADD CONSTRAINT FK_7FC19D2F33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (tournament_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_add_favorites_tournament ADD CONSTRAINT FK_2D4A983E7597D3FE FOREIGN KEY (member_id) REFERENCES `member` (member_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_add_favorites_tournament ADD CONSTRAINT FK_2D4A983E33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (tournament_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_moderate_roles ADD CONSTRAINT FK_E8D162F27597D3FE FOREIGN KEY (member_id) REFERENCES `member` (member_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_moderate_roles ADD CONSTRAINT FK_E8D162F269F79538 FOREIGN KEY (member_role_id) REFERENCES member_roles (member_role_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_history ADD CONSTRAINT FK_4CB58B4133D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (tournament_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_history ADD CONSTRAINT FK_4CB58B417597D3FE FOREIGN KEY (member_id) REFERENCES `member` (member_id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // Suppression dans l'ordre inverse (clés étrangères d'abord)
        $this->addSql('ALTER TABLE tournament_history DROP FOREIGN KEY FK_4CB58B4133D1A3E7');
        $this->addSql('ALTER TABLE tournament_history DROP FOREIGN KEY FK_4CB58B417597D3FE');
        $this->addSql('ALTER TABLE member_moderate_roles DROP FOREIGN KEY FK_E8D162F27597D3FE');
        $this->addSql('ALTER TABLE member_moderate_roles DROP FOREIGN KEY FK_E8D162F269F79538');
        $this->addSql('ALTER TABLE member_add_favorites_tournament DROP FOREIGN KEY FK_2D4A983E7597D3FE');
        $this->addSql('ALTER TABLE member_add_favorites_tournament DROP FOREIGN KEY FK_2D4A983E33D1A3E7');
        $this->addSql('ALTER TABLE member_register_tournament DROP FOREIGN KEY FK_7FC19D2F7597D3FE');
        $this->addSql('ALTER TABLE member_register_tournament DROP FOREIGN KEY FK_7FC19D2F33D1A3E7');
        $this->addSql('ALTER TABLE member_participate_tournament DROP FOREIGN KEY FK_89D6C1397597D3FE');
        $this->addSql('ALTER TABLE member_participate_tournament DROP FOREIGN KEY FK_89D6C13933D1A3E7');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D9DD1BC388');
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D97597D3FE');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA78C610EDD5');
        $this->addSql('ALTER TABLE `member` DROP FOREIGN KEY FK_70E4FA7869F79538');

        $this->addSql('DROP TABLE tournament_history');
        $this->addSql('DROP TABLE member_moderate_roles');
        $this->addSql('DROP TABLE member_add_favorites_tournament');
        $this->addSql('DROP TABLE member_register_tournament');
        $this->addSql('DROP TABLE member_participate_tournament');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE `member`');
        $this->addSql('DROP TABLE member_roles');
        $this->addSql('DROP TABLE member_avatars');
        $this->addSql('DROP TABLE tournament_images');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
