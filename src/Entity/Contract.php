<?php

namespace App\Entity;

use App\Repository\ContractRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContractRepository::class)]
class Contract
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxHours = null;

    #[ORM\Column(nullable: true)]
    private ?int $minBreakHours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getMaxHours(): ?int
    {
        return $this->maxHours;
    }

    public function setMaxHours(?int $maxHours): static
    {
        $this->maxHours = $maxHours;

        return $this;
    }

    public function getMinBreakHours(): ?int
    {
        return $this->minBreakHours;
    }

    public function setMinBreakHours(?int $minBreakHours): static
    {
        $this->minBreakHours = $minBreakHours;

        return $this;
    }
}
