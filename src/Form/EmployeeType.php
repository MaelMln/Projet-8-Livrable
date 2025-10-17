<?php

namespace App\Form;

use App\Entity\Employee;
use App\Enum\EmployeeStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('lastName', TextType::class, ['label' => 'Nom'])
			->add('firstName', TextType::class, ['label' => 'Prénom'])
			->add('email', EmailType::class, ['label' => 'Email'])
			->add('hiredAt', DateType::class, [
				'label' => "Date d'entrée",
				'widget' => 'single_text',
				'input' => 'datetime_immutable',
			])
			->add('status', EnumType::class, [
				'label' => 'Statut',
				'class' => EmployeeStatus::class,
				'choice_label' => fn(EmployeeStatus $s) => $s->value,
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults(['data_class' => Employee::class]);
	}
}
