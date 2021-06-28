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
     * @ORM\Column(type="integer")
     */
    private ?int $pokemonIdentifier;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $generation;

    /**
     * @ORM\ManyToMany(targetEntity=Move::class, mappedBy="pokemon")
     */
    private Collection $moves;

    /**
     * @ORM\OneToMany(targetEntity=PokemonName::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private $names;

    public function __construct()
    {
        $this->moves = new ArrayCollection();
        $this->names = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * @return Collection|Move[]
     */
    public function getMoves(): Collection
    {
        return $this->moves;
    }

    public function addMove(Move $move): self
    {
        if (!$this->moves->contains($move)) {
            $this->moves[] = $move;
            $move->addPokemon($this);
        }

        return $this;
    }

    public function removeMove(Move $move): self
    {
        if ($this->moves->removeElement($move)) {
            $move->removePokemon($this);
        }

        return $this;
    }

    /**
     * @return Collection|PokemonName[]
     */
    public function getNames(): Collection
    {
        return $this->names;
    }

    public function addName(PokemonName $name): self
    {
        if (!$this->names->contains($name)) {
            $this->names[] = $name;
            $name->setPokemon($this);
        }

        return $this;
    }

    public function removeName(PokemonName $name): self
    {
        if ($this->names->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getPokemon() === $this) {
                $name->setPokemon(null);
            }
        }

        return $this;
    }
}
