<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 */
class Pokemon
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
     * @ORM\ManyToOne(targetEntity=PokemonSpecy::class, inversedBy="pokemons")
     * @ORM\JoinColumn(nullable=false)
     */
    private PokemonSpecy $pokemonSpecy;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMove::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private Collection $pokemonMoves;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDefault;

    /**
     * @ORM\OneToMany(targetEntity=PokemonType::class, mappedBy="pokemon")
     */
    private $pokemonTypes;

    /**
     * @ORM\OneToMany(targetEntity=PokemonTypePast::class, mappedBy="pokemon")
     */
    private $pokemonTypePasts;

    /**
     * @ORM\Column(type="integer")
     */
    private $baseExperience;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     */
    private $pokemonOrder;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\OneToMany(targetEntity=PokemonGameIndex::class, mappedBy="pokemon")
     */
    private $pokemonGameIndices;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMoveAvailability::class, mappedBy="pokemon")
     */
    private $pokemonMoveAvailabilities;

    public function __construct()
    {
        $this->pokemonMoves = new ArrayCollection();
        $this->pokemonTypes = new ArrayCollection();
        $this->pokemonTypePasts = new ArrayCollection();
        $this->pokemonGameIndices = new ArrayCollection();
        $this->pokemonMoveAvailabilities = new ArrayCollection();
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

    public function getPokemonSpecy(): PokemonSpecy
    {
        return $this->pokemonSpecy;
    }

    public function setPokemonSpecy(PokemonSpecy $pokemonSpecy): self
    {
        $this->pokemonSpecy = $pokemonSpecy;

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
            $pokemonMove->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonMove(PokemonMove $pokemonMove): self
    {
        if ($this->pokemonMoves->removeElement($pokemonMove)) {
            // set the owning side to null (unless already changed)
            if ($pokemonMove->getPokemon() === $this) {
                $pokemonMove->setPokemon(null);
            }
        }

        return $this;
    }

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return Collection|PokemonType[]
     */
    public function getPokemonTypes(): Collection
    {
        return $this->pokemonTypes;
    }

    public function addPokemonType(PokemonType $pokemonType): self
    {
        if (!$this->pokemonTypes->contains($pokemonType)) {
            $this->pokemonTypes[] = $pokemonType;
            $pokemonType->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonType(PokemonType $pokemonType): self
    {
        if ($this->pokemonTypes->removeElement($pokemonType)) {
            // set the owning side to null (unless already changed)
            if ($pokemonType->getPokemon() === $this) {
                $pokemonType->setPokemon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonTypePast[]
     */
    public function getPokemonTypePasts(): Collection
    {
        return $this->pokemonTypePasts;
    }

    public function addPokemonTypePast(PokemonTypePast $pokemonTypePast): self
    {
        if (!$this->pokemonTypePasts->contains($pokemonTypePast)) {
            $this->pokemonTypePasts[] = $pokemonTypePast;
            $pokemonTypePast->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonTypePast(PokemonTypePast $pokemonTypePast): self
    {
        if ($this->pokemonTypePasts->removeElement($pokemonTypePast)) {
            // set the owning side to null (unless already changed)
            if ($pokemonTypePast->getPokemon() === $this) {
                $pokemonTypePast->setPokemon(null);
            }
        }

        return $this;
    }

    public function getBaseExperience(): ?int
    {
        return $this->baseExperience;
    }

    public function setBaseExperience(int $baseExperience): self
    {
        $this->baseExperience = $baseExperience;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getPokemonOrder(): ?int
    {
        return $this->pokemonOrder;
    }

    public function setPokemonOrder(int $pokemonOrder): self
    {
        $this->pokemonOrder = $pokemonOrder;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return Collection|PokemonGameIndex[]
     */
    public function getPokemonGameIndices(): Collection
    {
        return $this->pokemonGameIndices;
    }

    public function addPokemonGameIndex(PokemonGameIndex $pokemonGameIndex): self
    {
        if (!$this->pokemonGameIndices->contains($pokemonGameIndex)) {
            $this->pokemonGameIndices[] = $pokemonGameIndex;
            $pokemonGameIndex->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonGameIndex(PokemonGameIndex $pokemonGameIndex): self
    {
        if ($this->pokemonGameIndices->removeElement($pokemonGameIndex)) {
            // set the owning side to null (unless already changed)
            if ($pokemonGameIndex->getPokemon() === $this) {
                $pokemonGameIndex->setPokemon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonMoveAvailability[]
     */
    public function getPokemonMoveAvailabilities(): Collection
    {
        return $this->pokemonMoveAvailabilities;
    }

    public function addPokemonMoveAvailability(PokemonMoveAvailability $pokemonMoveAvailability): self
    {
        if (!$this->pokemonMoveAvailabilities->contains($pokemonMoveAvailability)) {
            $this->pokemonMoveAvailabilities[] = $pokemonMoveAvailability;
            $pokemonMoveAvailability->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonMoveAvailability(PokemonMoveAvailability $pokemonMoveAvailability): self
    {
        if ($this->pokemonMoveAvailabilities->removeElement($pokemonMoveAvailability)) {
            // set the owning side to null (unless already changed)
            if ($pokemonMoveAvailability->getPokemon() === $this) {
                $pokemonMoveAvailability->setPokemon(null);
            }
        }

        return $this;
    }
}
