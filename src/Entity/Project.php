<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 150)]
	#[Assert\NotBlank]
	private ?string $title = null;

	#[ORM\Column]
	private ?bool $archived = false;

	/**
	 * @var Collection<int, Employee>
	 */
	#[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'projects')]
	private Collection $members;

	/**
	 * @var Collection<int, Task>
	 */
	#[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project')]
	private Collection $tasks;

	public function __construct()
	{
		$this->members = new ArrayCollection();
		$this->tasks = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): static
	{
		$this->title = $title;
		return $this;
	}

	public function isArchived(): ?bool
	{
		return $this->archived;
	}

	public function setArchived(bool $archived): static
	{
		$this->archived = $archived;
		return $this;
	}

	/** @return Collection<int, Employee> */
	public function getMembers(): Collection
	{
		return $this->members;
	}

	public function addMember(Employee $member): static
	{
		if (!$this->members->contains($member)) {
			$this->members->add($member);
			$member->addProject($this);
		}
		return $this;
	}

	public function removeMember(Employee $member): static
	{
		if ($this->members->removeElement($member)) {
			$member->removeProject($this);
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
			$task->setProject($this);
		}
		return $this;
	}

	public function removeTask(Task $task): static
	{
		if ($this->tasks->removeElement($task)) {
			if ($task->getProject() === $this) {
				$task->setProject(null);
			}
		}
		return $this;
	}
}
