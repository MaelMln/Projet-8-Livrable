<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use App\Enum\TaskStatus;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		/** @var \App\Entity\Project|null $project */
		$project = $options['project'] ?? null;

		$builder
			->add('title', TextType::class, ['label' => 'Titre de la tâche'])
			->add('description', TextareaType::class, [
				'label' => 'Description',
				'required' => false,
			])
			->add('deadline', DateType::class, [
				'label' => 'Date',
				'required' => false,
				'input' => 'datetime_immutable',
				'widget' => 'single_text',
			])
			->add('status', EnumType::class, [
				'label' => 'Statut',
				'class' => TaskStatus::class,
				'choice_label' => fn(TaskStatus $s) => $s->label(),
			])
			->add('assignedTo', EntityType::class, [
				'class' => Employee::class,
				'required' => false,
				'label' => 'Membre',
				'placeholder' => '',
				'query_builder' => function (EntityRepository $er) use ($project) {
					$qb = $er->createQueryBuilder('e')->orderBy('e.lastName', 'ASC')->addOrderBy('e.firstName', 'ASC');
					if ($project) {
						$qb->innerJoin('e.projects', 'p')->andWhere('p = :project')->setParameter('project', $project);
					}
					return $qb;
				},
				'choice_label' => fn(Employee $e) => $e->getFullName(),
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Task::class,
			'project' => null,
		]);
		$resolver->setAllowedTypes('project', ['null', \App\Entity\Project::class]);
	}
}
