<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251017110226 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Migrate employee.status to a PHP enum (stored as VARCHAR(255)) and update the task.assigned_to_id foreign key (remove ON DELETE SET NULL).';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE employee CHANGE status status VARCHAR(255) NOT NULL');
		$this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25F4BD7827');
		$this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES employee (id)');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE employee CHANGE status status VARCHAR(50) NOT NULL');
		$this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25F4BD7827');
		$this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES employee (id) ON UPDATE NO ACTION ON DELETE SET NULL');
	}
}
