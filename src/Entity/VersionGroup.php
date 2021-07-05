<?php

namespace App\Entity;

use App\Repository\VersionGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VersionGroupRepository::class)
 */
class VersionGroup
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
     * @ORM\Column(type="integer")
     */
    private $versionGroupOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="versionGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="versionGroup", orphanRemoval=true)
     */
    private $machines;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMove::class, mappedBy="versionGroup", orphanRemoval=true)
     */
    private $pokemonMoves;

    public function __construct()
    {
        $this->machines = new ArrayCollection();
        $this->pokemonMoves = new ArrayCollection();
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

    public function getVersionGroupOrder(): ?int
    {
        return $this->versionGroupOrder;
    }

    public function setVersionGroupOrder(int $versionGroupOrder): self
    {
        $this->versionGroupOrder = $versionGroupOrder;

        return $this;
    }

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;

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
            $machine->setVersionGroup($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getVersionGroup() === $this) {
                $machine->setVersionGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonMove[]
     */
    public function getPokemonMoves(): Collection
    {
        return $this->pokemonMoves;
    }

    public function addPokemonMove(PokemonMove $pokemonMove): self
    {
        if (!$this->pokemonMoves->contains($pokemonMove)) {
            $this->pokemonMoves[] = $pokemonMove;
            $pokemonMove->setVersionGroup($this);
        }

        return $this;
    }

    public function removePokemonMove(PokemonMove $pokemonMove): self
    {
        if ($this->pokemonMoves->removeElement($pokemonMove)) {
            // set the owning side to null (unless already changed)
            if ($pokemonMove->getVersionGroup() === $this) {
                $pokemonMove->setVersionGroup(null);
            }
        }

        return $this;
    }
}
