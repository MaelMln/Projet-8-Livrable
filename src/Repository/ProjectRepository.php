<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Project::class);
	}

	/**
	 * @return Project[]
	 */
	public function findActive(): array
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.archived = :archived')->setParameter('archived', false)
			->orderBy('p.title', 'ASC')
			->getQuery()
			->getResult();
	}
}