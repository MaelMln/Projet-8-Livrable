<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Entity\Project;
use App\Entity\Task;
use App\Enum\EmployeeStatus;
use App\Enum\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager): void
	{
		$natalie = (new Employee())
			->setFirstName('Natalie')->setLastName('Dillon')
			->setEmail('natalie@driblet.com')->setStatus(EmployeeStatus::CDI)
			->setHiredAt(new \DateTimeImmutable('2019-06-14'));

		$demi = (new Employee())
			->setFirstName('Demi')->setLastName('Baker')
			->setEmail('demi@driblet.com')->setStatus(EmployeeStatus::CDD)
			->setHiredAt(new \DateTimeImmutable('2021-02-10'));

		$marie = (new Employee())
			->setFirstName('Marie')->setLastName('Dupont')
			->setEmail('marie@driblet.com')->setStatus(EmployeeStatus::FREELANCE)
			->setHiredAt(new \DateTimeImmutable('2023-01-05'));

		$manager->persist($natalie);
		$manager->persist($demi);
		$manager->persist($marie);

		$taskLinker = (new Project())->setTitle('TaskLinker')->setArchived(false);
		$siteSoeurs = (new Project())->setTitle('Site vitrine Les Soeurs Marchand')->setArchived(false);
		$archived = (new Project())->setTitle('Ancien projet archivé')->setArchived(true);

		$taskLinker->addMember($natalie)->addMember($demi);
		$siteSoeurs->addMember($natalie)->addMember($marie);

		$manager->persist($taskLinker);
		$manager->persist($siteSoeurs);
		$manager->persist($archived);

		$t1 = (new Task())
			->setProject($taskLinker)
			->setTitle("Gestion des droits d'accès")
			->setDescription("Un employé ne peut accéder qu’à ses projets")
			->setDeadline(new \DateTimeImmutable('2023-09-22'))
			->setStatus(TaskStatus::TODO);

		$t2 = (new Task())
			->setProject($taskLinker)
			->setTitle('Développement de la page employé')
			->setDescription("Liste/édition/suppression d’employés")
			->setDeadline(new \DateTimeImmutable('2023-09-14'))
			->setAssignedTo($demi)
			->setStatus(TaskStatus::DOING);

		$t3 = (new Task())
			->setProject($taskLinker)
			->setTitle('Développement de la structure globale')
			->setDescription('Intégrer les maquettes')
			->setDeadline(new \DateTimeImmutable('2023-09-03'))
			->setAssignedTo($demi)
			->setStatus(TaskStatus::DONE);

		$t4 = (new Task())
			->setProject($taskLinker)
			->setTitle('Développement de la page projet')
			->setDescription('Liste des tâches + C/E/S des tâches')
			->setStatus(TaskStatus::DONE)
			->setAssignedTo($natalie);

		$t5 = (new Task())
			->setProject($siteSoeurs)
			->setTitle('Recette v1')
			->setStatus(TaskStatus::TODO);

		foreach ([$t1, $t2, $t3, $t4, $t5] as $t) {
			$manager->persist($t);
		}

		$manager->flush();
	}
}