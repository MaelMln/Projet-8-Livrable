<?php

namespace App\Form;

use App\Entity\Employee;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
		$builder
			->add('lastName', TextType::class, [
				'label' => 'Nom',
				'attr' => ['placeholder' => 'Entrez votre nom'],
				'constraints' => [
					new NotBlank(['message' => 'Veuillez entrer votre nom']),
					new Length(['max' => 50]),
				],
			])
			->add('firstName', TextType::class, [
				'label' => 'Prénom',
				'attr' => ['placeholder' => 'Entrez votre prénom'],
				'constraints' => [
					new NotBlank(['message' => 'Veuillez entrer votre prénom']),
					new Length(['max' => 50]),
				],
			])
			->add('email', EmailType::class, [
				'label' => 'E-mail',
				'attr' => ['placeholder' => 'Entrez votre e-mail'],
				'constraints' => [
					new NotBlank(['message' => 'Veuillez entrer votre e-mail']),
					new Email(['message' => 'Veuillez entrer un e-mail valide']),
					new Length(['max' => 100]),
				],
			])
			->add('plainPassword', RepeatedType::class, [
				'type' => PasswordType::class,
				'mapped' => false,
				'first_options' => [
					'label' => 'Mot de passe',
					'attr' => ['placeholder' => 'Entrez votre mot de passe'],
				],
				'second_options' => [
					'label' => 'Confirmation mot de passe',
					'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
				],
				'invalid_message' => 'Les mots de passe doivent correspondre.',
				'constraints' => [
					new NotBlank(['message' => 'Veuillez entrer un mot de passe']),
					new Length([
						'min' => 6,
						'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
						'max' => 4096,
					]),
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
		$resolver->setDefaults([
			'data_class' => Employee::class,
		]);
	}
}
