<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Form\ProjectType;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectController extends AbstractController
{
	#[Route('/', name: 'app_project_index', methods: ['GET'])]
	public function index(ProjectRepository $projectRepository): Response
	{
		return $this->render('project/index.html.twig', [
			'projects' => $projectRepository->findActive(),
		]);
	}

	#[Route('/projects/new', name: 'app_project_new', methods: ['GET', 'POST'])]
	public function new(Request $request, EntityManagerInterface $em): Response
	{
		$project = new Project();
		$form = $this->createForm(ProjectType::class, $project);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($project);
			$em->flush();

			$this->addFlash('success', 'Projet créé.');
			return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
		}

		return $this->render('project/new.html.twig', ['form' => $form->createView()]);
	}

	#[Route('/projects/{id}', name: 'app_project_show', methods: ['GET'])]
	public function show(Project $project): Response
	{
		$tasksByStatus = [
			'todo' => [],
			'doing' => [],
			'done' => [],
		];

		foreach ($project->getTasks() as $t) {
			$key = $t->getStatus()?->value ?? 'todo';
			$tasksByStatus[$key][] = $t;
		}

		return $this->render('project/show.html.twig', [
			'project' => $project,
			'tasks_by_status' => $tasksByStatus,
		]);
	}

	#[Route('/projects/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])]
	public function edit(Project $project, Request $request, EntityManagerInterface $em): Response
	{
		$form = $this->createForm(ProjectType::class, $project);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();

			$this->addFlash('success', 'Projet modifié.');
			return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
		}

		return $this->render('project/edit.html.twig', [
			'form' => $form->createView(),
			'project' => $project
		]);
	}

	#[Route('/projects/{id}/archive', name: 'app_project_archive', methods: ['POST'])]
	public function archive(Request $request, Project $project, EntityManagerInterface $em): Response
	{
		if (!$this->isCsrfTokenValid('archive_project_' . $project->getId(), $request->request->get('_token'))) {
			$this->addFlash('error', 'Jeton CSRF invalide.');
			return $this->redirectToRoute('app_project_index');
		}

		$project->setArchived(true);
		$em->flush();

		$this->addFlash('success', 'Projet archivé.');
		return $this->redirectToRoute('app_project_index');
	}

	#[Route('/projects/{id}/tasks/new', name: 'app_task_new', methods: ['GET', 'POST'])]
	public function addTask(Project $project, Request $request, EntityManagerInterface $em): Response
	{
		$task = new Task();
		$task->setProject($project);
		$task->setStatus(TaskStatus::TODO);

		$form = $this->createForm(TaskType::class, $task, ['project' => $project]);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($task);
			$em->flush();

			$this->addFlash('success', 'Tâche ajoutée.');
			return $this->redirectToRoute('app_project_show', ['id' => $project->getId()]);
		}

		return $this->render('task/new.html.twig', [
			'form' => $form->createView(),
			'project' => $project
		]);
	}
}
