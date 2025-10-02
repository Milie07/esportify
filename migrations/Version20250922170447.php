<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922170447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member_avatars CHANGE code code INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F3D32B477153098 ON member_avatars (code)');
        $this->addSql('ALTER TABLE member_roles CHANGE member_role_label member_role_label VARCHAR(255) DEFAULT \'Player\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE member_roles CHANGE member_role_label member_role_label VARCHAR(20) DEFAULT \'Player\' NOT NULL');
        $this->addSql('DROP INDEX UNIQ_F3D32B477153098 ON member_avatars');
        $this->addSql('ALTER TABLE member_avatars CHANGE code code INT UNSIGNED NOT NULL');
    }
}
