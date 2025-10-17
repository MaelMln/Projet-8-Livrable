<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tasks')]
class TaskController extends AbstractController
{
	#[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
	public function edit(Task $task, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(TaskType::class, $task, ['project' => $task->getProject()]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$this->addFlash('success', 'Tâche modifiée.');
			return $this->redirectToRoute('app_project_show', ['id' => $task->getProject()->getId()]);
		}

		return $this->render('task/edit.html.twig', [
			'form' => $form->createView(),
			'task' => $task
		]);
	}

	#[Route('/{id}/delete', name: 'app_task_delete', methods: ['POST'])]
	public function delete(Request $request, Task $task, EntityManagerInterface $em): Response
	{
		if (!$this->isCsrfTokenValid('delete_task_' . $task->getId(), $request->request->get('_token'))) {
			$this->addFlash('error', 'Jeton CSRF invalide.');
			return $this->redirectToRoute('app_project_show', ['id' => $task->getProject()->getId()]);
		}

		$projectId = $task->getProject()->getId();
		$em->remove($task);
		$em->flush();

		$this->addFlash('success', 'Tâche supprimée.');
		return $this->redirectToRoute('app_project_show', ['id' => $projectId]);
	}
}
