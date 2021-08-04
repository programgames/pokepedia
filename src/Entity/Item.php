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
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=ItemName::class, mappedBy="item")
     */
    private Collection $itemNames;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="item")
     */
    private Collection $machines;

    /**
     * @ORM\OneToMany(targetEntity=EvolutionChain::class, mappedBy="babyTriggerItem")
     */
    private $evolutionChains;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $flingPower;

    /**
     * @ORM\Column(type="integer")
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity=ItemFlingEffect::class, inversedBy="items")
     */
    private $itemFlingEffect;

    /**
     * @ORM\ManyToOne(targetEntity=ItemCategory::class, inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     */
    private $itemCategory;

    public function __construct()
    {
        $this->itemNames = new ArrayCollection();
        $this->machines = new ArrayCollection();
        $this->evolutionChains = new ArrayCollection();
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

    /**
     * @return Collection|EvolutionChain[]
     */
    public function getEvolutionChains(): Collection
    {
        return $this->evolutionChains;
    }

    public function addEvolutionChain(EvolutionChain $evolutionChain): self
    {
        if (!$this->evolutionChains->contains($evolutionChain)) {
            $this->evolutionChains[] = $evolutionChain;
            $evolutionChain->setBabyTriggerItem($this);
        }

        return $this;
    }

    public function removeEvolutionChain(EvolutionChain $evolutionChain): self
    {
        if ($this->evolutionChains->removeElement($evolutionChain)) {
            // set the owning side to null (unless already changed)
            if ($evolutionChain->getBabyTriggerItem() === $this) {
                $evolutionChain->setBabyTriggerItem(null);
            }
        }

        return $this;
    }

    public function getFlingPower(): ?int
    {
        return $this->flingPower;
    }

    public function setFlingPower(?int $flingPower): self
    {
        $this->flingPower = $flingPower;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getItemFlingEffect(): ?ItemFlingEffect
    {
        return $this->itemFlingEffect;
    }

    public function setItemFlingEffect(?ItemFlingEffect $itemFlingEffect): self
    {
        $this->itemFlingEffect = $itemFlingEffect;

        return $this;
    }

    public function getItemCategory(): ItemCategory
    {
        return $this->itemCategory;
    }

    public function setItemCategory(ItemCategory $itemCategory): self
    {
        $this->itemCategory = $itemCategory;

        return $this;
    }
}
