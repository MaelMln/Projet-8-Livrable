<?php

namespace App\Entity;

use App\Enum\EmployeeStatus;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé.')]
class Employee
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 50)]
	#[Assert\NotBlank]
	#[Assert\Length(max: 50)]
	private ?string $firstName = null;

	#[ORM\Column(length: 50)]
	#[Assert\NotBlank]
	#[Assert\Length(max: 50)]
	private ?string $lastName = null;

	#[ORM\Column(length: 100, unique: true)]
	#[Assert\NotBlank]
	#[Assert\Email]
	#[Assert\Length(max: 100)]
	private ?string $email = null;

	#[ORM\Column(enumType: EmployeeStatus::class)]
	#[Assert\NotNull]
	private ?EmployeeStatus $status = null;

	#[ORM\Column]
	#[Assert\NotNull]
	#[Assert\LessThanOrEqual('today')]
	private ?\DateTimeImmutable $hiredAt = null;

	/**
	 * @var Collection<int, Project>
	 */
	#[ORM\ManyToMany(targetEntity: Project::class, mappedBy: 'members')]
	private Collection $projects;

	/**
	 * @var Collection<int, Task>
	 */
	#[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'assignedTo')]
	private Collection $tasks;

	public function __construct()
	{
		$this->projects = new ArrayCollection();
		$this->tasks = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): static
	{
		$this->firstName = $firstName;
		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): static
	{
		$this->lastName = $lastName;
		return $this;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): static
	{
		$this->email = $email;
		return $this;
	}

	public function getStatus(): ?EmployeeStatus
	{
		return $this->status;
	}

	public function setStatus(EmployeeStatus $status): static
	{
		$this->status = $status;
		return $this;
	}

	public function getHiredAt(): ?\DateTimeImmutable
	{
		return $this->hiredAt;
	}

	public function setHiredAt(\DateTimeImmutable $hiredAt): static
	{
		$this->hiredAt = $hiredAt;
		return $this;
	}

	/** @return Collection<int, Project> */
	public function getProjects(): Collection
	{
		return $this->projects;
	}

	public function addProject(Project $project): static
	{
		if (!$this->projects->contains($project)) {
			$this->projects->add($project);
			$project->addMember($this);
		}
		return $this;
	}

	public function removeProject(Project $project): static
	{
		if ($this->projects->removeElement($project)) {
			$project->removeMember($this);
		}
		return $this;
	}

	/** @return Collection<int, Task> */
	public function getTasks(): Collection
	{
		return $this->tasks;
	}

	public function addTask(Task $task): static
	{
		if (!$this->tasks->contains($task)) {
			$this->tasks->add($task);
			$task->setAssignedTo($this);
		}
		return $this;
	}

	public function removeTask(Task $task): static
	{
		if ($this->tasks->removeElement($task)) {
			if ($task->getAssignedTo() === $this) {
				$task->setAssignedTo(null);
			}
		}
		return $this;
	}

	public function getFullName(): string
	{
		return trim(($this->firstName ?? '') . ' ' . ($this->lastName ?? ''));
	}

	public function getInitials(): string
	{
		$f = $this->firstName ? mb_substr($this->firstName, 0, 1) : '';
		$l = $this->lastName ? mb_substr($this->lastName, 0, 1) : '';
		return mb_strtoupper($f . $l);
	}
}
