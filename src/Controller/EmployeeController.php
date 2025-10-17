<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Form\EmployeeType;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/employees')]
class EmployeeController extends AbstractController
{
	#[Route('', name: 'app_employee_index', methods: ['GET'])]
	public function index(EmployeeRepository $repo): Response
	{
		return $this->render('employee/index.html.twig', [
			'employees' => $repo->findBy([], ['lastName' => 'ASC', 'firstName' => 'ASC']),
		]);
	}

	#[Route('/{id}/edit', name: 'app_employee_edit', methods: ['GET', 'POST'])]
	public function edit(Employee $employee, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(EmployeeType::class, $employee);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$this->addFlash('success', 'Employé mis à jour.');
			return $this->redirectToRoute('app_employee_index');
		}

		return $this->render('employee/edit.html.twig', [
			'form' => $form->createView(),
			'employee' => $employee,
		]);
	}

	#[Route('/{id}/delete', name: 'app_employee_delete', methods: ['POST'])]
	public function delete(Request $request, Employee $employee, EntityManagerInterface $em): Response
	{
		if (!$this->isCsrfTokenValid('delete_employee_' . $employee->getId(), $request->request->get('_token'))) {
			$this->addFlash('error', 'Jeton CSRF invalide.');
			return $this->redirectToRoute('app_employee_index');
		}

		$em->remove($employee);
		$em->flush();

		$this->addFlash('success', 'Employé supprimé.');
		return $this->redirectToRoute('app_employee_index');
	}
}
