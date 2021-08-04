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
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=MoveName::class, mappedBy="move")
     */
    private Collection $moveNames;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="move")
     */
    private Collection $machines;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMove::class, mappedBy="move", orphanRemoval=true)
     */
    private Collection $pokemonMoves;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accuracy;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $moveEffectChance;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="moves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

    /**
     * @ORM\ManyToOne(targetEntity=MoveDamageClass::class, inversedBy="moves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $moveDamageClass;

    /**
     * @ORM\ManyToOne(targetEntity=ContestEffect::class, inversedBy="moves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $contestEffect;

    /**
     * @ORM\ManyToOne(targetEntity=MoveTarget::class, inversedBy="moves")
     * @ORM\JoinColumn(nullable=false)
     */
    private $moveTarget;

    /**
     * @ORM\ManyToOne(targetEntity=ContestType::class, inversedBy="moves")
     * @ORM\JoinColumn(nullable=true)
     */
    private $contestType;

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

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy(?int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getMoveEffectChance(): ?int
    {
        return $this->moveEffectChance;
    }

    public function setMoveEffectChance(?int $moveEffectChance): self
    {
        $this->moveEffectChance = $moveEffectChance;

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

    public function getMoveDamageClass(): ?MoveDamageClass
    {
        return $this->moveDamageClass;
    }

    public function setMoveDamageClass(?MoveDamageClass $moveDamageClass): self
    {
        $this->moveDamageClass = $moveDamageClass;

        return $this;
    }

    public function getContestEffect(): ?ContestEffect
    {
        return $this->contestEffect;
    }

    public function setContestEffect(?ContestEffect $contestEffect): self
    {
        $this->contestEffect = $contestEffect;

        return $this;
    }

    public function getMoveTarget(): ?MoveTarget
    {
        return $this->moveTarget;
    }

    public function setMoveTarget(?MoveTarget $moveTarget): self
    {
        $this->moveTarget = $moveTarget;

        return $this;
    }

    public function getContestType(): ?ContestType
    {
        return $this->contestType;
    }

    public function setContestType(?ContestType $contestType): self
    {
        $this->contestType = $contestType;

        return $this;
    }
}
