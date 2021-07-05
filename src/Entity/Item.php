<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ItemName::class, mappedBy="item")
     */
    private $itemNames;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="item")
     */
    private $machines;

    public function __construct()
    {
        $this->itemNames = new ArrayCollection();
        $this->machines = new ArrayCollection();
    }

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

    /**
     * @return Collection|ItemName[]
     */
    public function getItemNames(): Collection
    {
        return $this->itemNames;
    }

    public function addItemName(ItemName $itemName): self
    {
        if (!$this->itemNames->contains($itemName)) {
            $this->itemNames[] = $itemName;
            $itemName->setItem($this);
        }

        return $this;
    }

    public function removeItemName(ItemName $itemName): self
    {
        if ($this->itemNames->removeElement($itemName)) {
            // set the owning side to null (unless already changed)
            if ($itemName->getItem() === $this) {
                $itemName->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Machine[]
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): self
    {
        if (!$this->machines->contains($machine)) {
            $this->machines[] = $machine;
            $machine->setItem($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getItem() === $this) {
                $machine->setItem(null);
            }
        }

        return $this;
    }
}
