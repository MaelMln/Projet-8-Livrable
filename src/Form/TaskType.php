<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Task;
use App\Enum\TaskStatus;
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
		$project = $options['project'];
		$memberChoices = $project ? $project->getMembers()->toArray() : [];

		$builder
			->add('title', TextType::class, [
				'label' => 'Titre de la tâche',
			])
			->add('description', TextareaType::class, [
				'label' => 'Description',
				'required' => false,
				'attr' => ['rows' => 4],
			])
			->add('deadline', DateType::class, [
				'label' => 'Date',
				'widget' => 'single_text',
				'required' => false,
				'input' => 'datetime_immutable',
			])
			->add('status', EnumType::class, [
				'label' => 'Statut',
				'class' => TaskStatus::class,
				'choice_label' => fn(TaskStatus $s) => $s->label(),
			])
			->add('assignedTo', EntityType::class, [
				'label' => 'Membre',
				'class' => Employee::class,
				'choices' => $memberChoices,
				'placeholder' => '',
				'required' => false,
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(['data_class' => Task::class, 'project' => null]);
		$resolver->setAllowedTypes('project', ['null', 'App\Entity\Project']);
	}
}
