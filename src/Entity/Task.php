<?php

namespace App\Entity;

use App\Enum\TaskStatus;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 150)]
	#[Assert\NotBlank]
	private ?string $title = null;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	private ?string $description = null;

	#[ORM\Column(nullable: true)]
	private ?\DateTimeImmutable $deadline = null;

	#[ORM\Column(enumType: TaskStatus::class)]
	#[Assert\NotNull]
	private ?TaskStatus $status = null;

	#[ORM\ManyToOne(inversedBy: 'tasks')]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotNull]
	private ?Project $project = null;

	#[ORM\ManyToOne(inversedBy: 'tasks')]
	private ?Employee $assignedTo = null;

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

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): static
	{
		$this->description = $description;
		return $this;
	}

	public function getDeadline(): ?\DateTimeImmutable
	{
		return $this->deadline;
	}

	public function setDeadline(?\DateTimeImmutable $deadline): static
	{
		$this->deadline = $deadline;
		return $this;
	}

	public function getStatus(): ?TaskStatus
	{
		return $this->status;
	}

	public function setStatus(TaskStatus $status): static
	{
		$this->status = $status;
		return $this;
	}

	public function getProject(): ?Project
	{
		return $this->project;
	}

	public function setProject(?Project $project): static
	{
		$this->project = $project;
		return $this;
	}

	public function getAssignedTo(): ?Employee
	{
		return $this->assignedTo;
	}

	public function setAssignedTo(?Employee $assignedTo): static
	{
		$this->assignedTo = $assignedTo;
		return $this;
	}
}