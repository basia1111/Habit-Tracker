<?php

namespace App\Entity;

use App\Repository\HabitRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitRepository::class)]
class Habit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $time = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $place = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $requisites = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notes = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $streak = 0;

    #[ORM\Column]
    private ?int $bestStreak = 0;

    #[ORM\Column]
    private ?bool $monday = false;

    #[ORM\Column]
    private ?bool $tusday = false;

    #[ORM\Column]
    private ?bool $wednesday = false;

    #[ORM\Column]
    private ?bool $thursday = false;

    #[ORM\Column]
    private ?bool $friday = false;

    #[ORM\Column]
    private ?bool $sathurday = false;

    #[ORM\Column]
    private ?bool $sunday = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastExecution = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(?string $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getRequisites(): ?string
    {
        return $this->requisites;
    }

    public function setRequisites(?string $requisites): self
    {
        $this->requisites = $requisites;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStreak(): ?int
    {
        return $this->streak;
    }

    public function setStreak(int $streak): self
    {
        $this->streak = $streak;

        return $this;
    }

    public function getBestStreak(): ?int
    {
        return $this->bestStreak;
    }

    public function setBestStreak(int $bestStreak): self
    {
        $this->bestStreak = $bestStreak;

        return $this;
    }

    public function isMonday(): ?bool
    {
        return $this->monday;
    }

    public function setMonday(bool $monday): self
    {
        $this->monday = $monday;

        return $this;
    }

    public function isTusday(): ?bool
    {
        return $this->tusday;
    }

    public function setTusday(bool $tusday): self
    {
        $this->tusday = $tusday;

        return $this;
    }

    public function isWednesday(): ?bool
    {
        return $this->wednesday;
    }

    public function setWednesday(bool $wednesday): self
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    public function isThursday(): ?bool
    {
        return $this->thursday;
    }

    public function setThursday(bool $thursday): self
    {
        $this->thursday = $thursday;

        return $this;
    }

    public function isFriday(): ?bool
    {
        return $this->friday;
    }

    public function setFriday(bool $friday): self
    {
        $this->friday = $friday;

        return $this;
    }

    public function isSathurday(): ?bool
    {
        return $this->sathurday;
    }

    public function setSathurday(bool $sathurday): self
    {
        $this->sathurday = $sathurday;

        return $this;
    }

    public function isSunday(): ?bool
    {
        return $this->sunday;
    }

    public function setSunday(bool $sunday): self
    {
        $this->sunday = $sunday;

        return $this;
    }

    public function getLastExecution(): ?\DateTimeInterface
    {
        return $this->lastExecution;
    }

    public function setLastExecution(?\DateTimeInterface $lastExecution): self
    {
        $this->lastExecution = $lastExecution;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
