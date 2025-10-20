<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Enum\EmployeeStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture
{
	public const EMP_NATALIE = 'emp_natalie';
	public const EMP_DEMI    = 'emp_demi';
	public const EMP_MARIE   = 'emp_marie';

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

		$manager->flush();

		$this->addReference(self::EMP_NATALIE, $natalie);
		$this->addReference(self::EMP_DEMI, $demi);
		$this->addReference(self::EMP_MARIE, $marie);
	}
}
