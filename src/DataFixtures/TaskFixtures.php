<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Enum\TaskStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager): void
	{
		$taskLinker = $this->getReference(ProjectFixtures::PROJ_TASKLINKER);
		$siteSoeurs = $this->getReference(ProjectFixtures::PROJ_SOEURS);

		$natalie = $this->getReference(EmployeeFixtures::EMP_NATALIE);
		$demi    = $this->getReference(EmployeeFixtures::EMP_DEMI);

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

	public function getDependencies(): array
	{
		return [ProjectFixtures::class, EmployeeFixtures::class];
	}
}
