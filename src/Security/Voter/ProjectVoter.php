<?php

namespace App\Security\Voter;

use App\Entity\Employee;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ProjectVoter extends Voter
{
	public function __construct(
		private ProjectRepository $projectRepository,
		private TaskRepository $taskRepository,
	) {
	}

	protected function supports(string $attribute, mixed $subject): bool
	{
		return $attribute === 'acces_projet' || $attribute === 'acces_tache';
	}

	protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
	{
		// Récupérer le projet concerné
		if ($attribute === 'acces_projet') {
			$project = $this->projectRepository->find($subject);
		} else {
			$task = $this->taskRepository->find($subject);
			$project = $task?->getProject();
		}

		$user = $token->getUser();

		// Si l'utilisateur n'est pas connecté ou le projet n'existe pas
		if (!$user instanceof UserInterface || !$project) {
			return false;
		}

		// Les admins ont accès à tout, sinon vérifier si membre du projet
		return $user->isAdmin() || $project->getMembers()->contains($user);
	}
}
