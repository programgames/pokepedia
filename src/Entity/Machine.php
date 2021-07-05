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
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $machineNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Move::class, inversedBy="machines")
     */
    private $move;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="machines")
     */
    private $item;

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
}
