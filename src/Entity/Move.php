<?php

namespace App\Entity;

use App\Repository\MoveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoveRepository::class)
 */
class Move
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
     * @ORM\OneToMany(targetEntity=MoveName::class, mappedBy="move")
     */
    private $moveNames;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="move")
     */
    private $machines;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMove::class, mappedBy="move", orphanRemoval=true)
     */
    private $pokemonMoves;

    public function __construct()
    {
        $this->moveNames = new ArrayCollection();
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

    /**
     * @return Collection|MoveName[]
     */
    public function getMoveNames(): Collection
    {
        return $this->moveNames;
    }

    public function addMoveName(MoveName $moveName): self
    {
        if (!$this->moveNames->contains($moveName)) {
            $this->moveNames[] = $moveName;
            $moveName->setMove($this);
        }

        return $this;
    }

    public function removeMoveName(MoveName $moveName): self
    {
        if ($this->moveNames->removeElement($moveName)) {
            // set the owning side to null (unless already changed)
            if ($moveName->getMove() === $this) {
                $moveName->setMove(null);
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
            $machine->setMove($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getMove() === $this) {
                $machine->setMove(null);
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
            $pokemonMove->setMove($this);
        }

        return $this;
    }

    public function removePokemonMove(PokemonMove $pokemonMove): self
    {
        if ($this->pokemonMoves->removeElement($pokemonMove)) {
            // set the owning side to null (unless already changed)
            if ($pokemonMove->getMove() === $this) {
                $pokemonMove->setMove(null);
            }
        }

        return $this;
    }
}
