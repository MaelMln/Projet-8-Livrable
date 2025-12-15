<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Enum\EmployeeStatus;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	#[Route('/inscription', name: 'app_register')]
	public function register(
		Request $request,
		UserPasswordHasherInterface $passwordHasher,
		EntityManagerInterface $entityManager
	): Response {
		$employee = new Employee();
		$form = $this->createForm(RegistrationFormType::class, $employee);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			// Hacher le mot de passe
			$hashedPassword = $passwordHasher->hashPassword(
				$employee,
				$form->get('plainPassword')->getData()
			);
			$employee->setPassword($hashedPassword);

			// Définir les valeurs par défaut
			$employee->setStatus(EmployeeStatus::CDI);
			$employee->setHiredAt(new \DateTimeImmutable());

			// Rôle par défaut : collaborateur
			$employee->setRoles(['ROLE_USER']);

			$entityManager->persist($employee);
			$entityManager->flush();

			$this->addFlash('success', 'Votre compte a été créé avec succès !');

			return $this->redirectToRoute('app_login');
		}

		return $this->render('security/register.html.twig', [
			'registrationForm' => $form,
		]);
	}

	#[Route('/connexion', name: 'app_login')]
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		// Récupérer l'erreur de connexion s'il y en a une
		$error = $authenticationUtils->getLastAuthenticationError();

		// Dernier nom d'utilisateur entré
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', [
			'last_username' => $lastUsername,
			'error' => $error,
		]);
	}

	#[Route('/deconnexion', name: 'app_logout')]
	public function logout(): void
	{
		// Cette méthode sera gérée automatiquement par Symfony
		throw new \Exception('Cette méthode devrait être interceptée par Symfony.');
	}
}
