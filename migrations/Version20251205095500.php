<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration pour ajouter les champs d'authentification à l'entité Employee
 */
final class Version20251205095500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ajoute les champs password et roles à la table employee pour l\'authentification';
    }

    public function up(Schema $schema): void
    {
        // Ajouter le champ password
        $this->addSql('ALTER TABLE employee ADD password VARCHAR(255) NOT NULL DEFAULT ""');

        // Ajouter le champ roles (JSON)
        $this->addSql('ALTER TABLE employee ADD roles JSON NOT NULL DEFAULT "[]"');
    }

    public function down(Schema $schema): void
    {
        // Supprimer les champs en cas de rollback
        $this->addSql('ALTER TABLE employee DROP password');
        $this->addSql('ALTER TABLE employee DROP roles');
    }
}
