<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $week = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: Shift::class, mappedBy: 'schedule')]
    private Collection $shifts;

    #[ORM\ManyToOne(targetEntity: Employee::class)]
    private Employee $employee;

    public function __construct()
    {
        $this->shifts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeek(): ?\DateTimeInterface
    {
        return $this->week;
    }

    public function setWeek(\DateTimeInterface $week): static
    {
        $this->week = $week;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Shift>
     */
    public function getShifts(): Collection
    {
        return $this->shifts;
    }

    public function addShift(Shift $shift): static
    {
        if (!$this->shifts->contains($shift)) {
            $this->shifts->add($shift);
            $shift->setSchedule($this);
        }

        return $this;
    }

    public function removeShift(Shift $shift): static
    {
        if ($this->shifts->removeElement($shift)) {
            if ($shift->getSchedule() === $this) {
                $shift->setSchedule(null);
            }
        }

        return $this;
    }

    public function getEmployee(): Employee
    {
        return $this->employee;
    }

}
