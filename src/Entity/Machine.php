<?php

namespace App\Entity;

use App\Repository\MachineRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MachineRepository::class)
 */
class Machine
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $machineNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Move::class, inversedBy="machines")
     */
    private ?Move $move;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="machines")
     */
    private ?Item $item;

    /**
     * @ORM\ManyToOne(targetEntity=VersionGroup::class, inversedBy="machines")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?VersionGroup $versionGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMachineNumber(): ?int
    {
        return $this->machineNumber;
    }

    public function setMachineNumber(int $machineNumber): self
    {
        $this->machineNumber = $machineNumber;

        return $this;
    }

    public function getMove(): ?Move
    {
        return $this->move;
    }

    public function setMove(?Move $move): self
    {
        $this->move = $move;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getVersionGroup(): ?VersionGroup
    {
        return $this->versionGroup;
    }

    public function setVersionGroup(?VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;

        return $this;
    }
}
