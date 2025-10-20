<?php

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements DependentFixtureInterface
{
	public const PROJ_TASKLINKER = 'proj_tasklinker';
	public const PROJ_SOEURS = 'proj_soeurs';
	public const PROJ_ARCHIVED = 'proj_archived';

	public function load(ObjectManager $manager): void
	{
		$taskLinker = (new Project())->setTitle('TaskLinker')->setArchived(false);
		$siteSoeurs = (new Project())->setTitle('Site vitrine Les Soeurs Marchand')->setArchived(false);
		$archived   = (new Project())->setTitle('Ancien projet archivé')->setArchived(true);

		$natalie = $this->getReference(EmployeeFixtures::EMP_NATALIE);
		$demi    = $this->getReference(EmployeeFixtures::EMP_DEMI);
		$marie   = $this->getReference(EmployeeFixtures::EMP_MARIE);

		$taskLinker->addMember($natalie)->addMember($demi);
		$siteSoeurs->addMember($natalie)->addMember($marie);

		$manager->persist($taskLinker);
		$manager->persist($siteSoeurs);
		$manager->persist($archived);
		$manager->flush();

		$this->addReference(self::PROJ_TASKLINKER, $taskLinker);
		$this->addReference(self::PROJ_SOEURS, $siteSoeurs);
		$this->addReference(self::PROJ_ARCHIVED, $archived);
	}

	public function getDependencies(): array
	{
		return [EmployeeFixtures::class];
	}
}
