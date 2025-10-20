<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251017082739 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Initial schema: create tables employee, project, task, and project_employee; add indexes, unique constraints, and foreign keys (task.assigned_to_id uses ON DELETE SET NULL).';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, status VARCHAR(50) NOT NULL, hired_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5D9F75A1E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(150) NOT NULL, archived TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE project_employee (project_id INT NOT NULL, employee_id INT NOT NULL, INDEX IDX_60D1FE7A166D1F9C (project_id), INDEX IDX_60D1FE7A8C03F15C (employee_id), PRIMARY KEY(project_id, employee_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, assigned_to_id INT DEFAULT NULL, title VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, deadline DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, INDEX IDX_527EDB25166D1F9C (project_id), INDEX IDX_527EDB25F4BD7827 (assigned_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('ALTER TABLE project_employee ADD CONSTRAINT FK_60D1FE7A166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
		$this->addSql('ALTER TABLE project_employee ADD CONSTRAINT FK_60D1FE7A8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) ON DELETE CASCADE');
		$this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
		$this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES employee (id) ON DELETE SET NULL');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('ALTER TABLE project_employee DROP FOREIGN KEY FK_60D1FE7A166D1F9C');
		$this->addSql('ALTER TABLE project_employee DROP FOREIGN KEY FK_60D1FE7A8C03F15C');
		$this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25166D1F9C');
		$this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25F4BD7827');
		$this->addSql('DROP TABLE employee');
		$this->addSql('DROP TABLE project');
		$this->addSql('DROP TABLE project_employee');
		$this->addSql('DROP TABLE task');
	}
}
