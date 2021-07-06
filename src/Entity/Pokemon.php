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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $pokemonOrder;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonSpecy::class, inversedBy="pokemons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemonSpecy;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMove::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private $pokemonMoves;

    /**
     * @ORM\Column(type="integer")
     */
    private $pokemonIdentifier;

    public function __construct()
    {
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

    public function getPokemonOrder(): ?int
    {
        return $this->pokemonOrder;
    }

    public function setPokemonOrder(int $pokemonOrder): self
    {
        $this->pokemonOrder = $pokemonOrder;

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

    public function getPokemonIdentifier(): ?int
    {
        return $this->pokemonIdentifier;
    }

    public function setPokemonIdentifier(int $pokemonIdentifier): self
    {
        $this->pokemonIdentifier = $pokemonIdentifier;

        return $this;
    }
}
