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
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonSpecy::class, inversedBy="pokemons")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?PokemonSpecy $pokemonSpecy;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMove::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private Collection $pokemonMoves;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $specificName;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isAlola = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isGalar = false;

    /**
     * @ORM\OneToMany(targetEntity=PokemonAvailability::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private Collection $pokemonAvailabilities;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDefault;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $hasMoveForms = false;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class)
     */
    private Collection $forms;

    /**
     * @ORM\OneToOne(targetEntity=BaseInformation::class, inversedBy="pokemon", cascade={"persist", "remove"})
     */
    private $baseInformation;

    /**
     * @ORM\OneToOne(targetEntity=PokemonName::class, inversedBy="pokemon", cascade={"persist", "remove"})
     */
    private $pokemonName;

    public function __construct()
    {
        $this->pokemonMoves = new ArrayCollection();
        $this->pokemonAvailabilities = new ArrayCollection();
        $this->forms = new ArrayCollection();
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

    public function getPokemonSpecy(): ?PokemonSpecy
    {
        return $this->pokemonSpecy;
    }

    public function setPokemonSpecy(?PokemonSpecy $pokemonSpecy): self
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

    public function getSpecificName(): ?string
    {
        return $this->specificName;
    }

    public function setSpecificName(?string $specificName): self
    {
        $this->specificName = $specificName;

        return $this;
    }

    public function isAlola(): ?bool
    {
        return $this->isAlola;
    }

    public function setIsAlola(bool $isAlola): self
    {
        $this->isAlola = $isAlola;

        return $this;
    }

    public function getIsGalar(): ?bool
    {
        return $this->isGalar;
    }

    public function setIsGalar(bool $isGalar): self
    {
        $this->isGalar = $isGalar;

        return $this;
    }

    /**
     * @return Collection|PokemonAvailability[]
     */
    public function getPokemonAvailabilities(): Collection
    {
        return $this->pokemonAvailabilities;
    }

    public function addPokemonAvailability(PokemonAvailability $pokemonAvailability): self
    {
        if (!$this->pokemonAvailabilities->contains($pokemonAvailability)) {
            $this->pokemonAvailabilities[] = $pokemonAvailability;
            $pokemonAvailability->setPokemon($this);
        }

        return $this;
    }

    public function removePokemonAvailability(PokemonAvailability $pokemonAvailability): self
    {
        if ($this->pokemonAvailabilities->removeElement($pokemonAvailability)) {
            // set the owning side to null (unless already changed)
            if ($pokemonAvailability->getPokemon() === $this) {
                $pokemonAvailability->setPokemon(null);
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

    public function getHasMoveForms(): ?bool
    {
        return $this->hasMoveForms;
    }

    public function setHasMoveForms(bool $hasMoveForms): self
    {
        $this->hasMoveForms = $hasMoveForms;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getForms(): Collection
    {
        return $this->forms;
    }

    public function addForm(self $form): self
    {
        if (!$this->forms->contains($form)) {
            $this->forms[] = $form;
        }

        return $this;
    }

    public function removeForm(self $form): self
    {
        $this->forms->removeElement($form);

        return $this;
    }

    public function getBaseInformation(): ?BaseInformation
    {
        return $this->baseInformation;
    }

    public function setBaseInformation(?BaseInformation $baseInformation): self
    {
        $this->baseInformation = $baseInformation;

        return $this;
    }

    public function getPokemonName(): ?PokemonName
    {
        return $this->pokemonName;
    }

    public function setPokemonName(?PokemonName $pokemonName): self
    {
        $this->pokemonName = $pokemonName;

        return $this;
    }
}
