<?php

namespace App\Entity;

use App\Controller\ReportController;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
class Employee
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 80)]
    private ?string $surname = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $contractType = null;

    #[ORM\Column(nullable: true)]
    private ?int $monthlyHours = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\OneToMany(targetEntity: Shift::class, mappedBy: 'employee')]
    private Collection $shifts;

    #[ORM\OneToMany(targetEntity: Leave::class, mappedBy: 'employee')]
    private Collection $leaves;

    public function __construct()
    {
        $this->shifts = new ArrayCollection();
        $this->leaves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): static
    {
        $this->surname = $surname;

        return $this;
    }

    public function getContractType(): ?string
    {
        return $this->contractType;
    }

    public function setContractType(?string $contractType): static
    {
        $this->contractType = $contractType;

        return $this;
    }

    public function getMonthlyHours(): ?int
    {
        return $this->monthlyHours;
    }

    public function setMonthlyHours(?int $monthlyHours): static
    {
        $this->monthlyHours = $monthlyHours;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

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
            $shift->setEmployee($this);
        }

        return $this;
    }

    public function removeShift(Shift $shift): static
    {
        if ($this->shifts->removeElement($shift)) {
            if ($shift->getEmployee() === $this) {
                $shift->setEmployee(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Leave>
     */
    public function getLeaves(): Collection
    {
        return $this->leaves;
    }

    public function addLeaf(Leave $leaf): static
    {
        if (!$this->leaves->contains($leaf)) {
            $this->leaves->add($leaf);
            $leaf->setEmployee($this);
        }

        return $this;
    }

    public function removeLeaf(Leave $leaf): static
    {
        if ($this->leaves->removeElement($leaf)) {
            if ($leaf->getEmployee() === $this) {
                $leaf->setEmployee(null);
            }
        }

        return $this;
    }
}
