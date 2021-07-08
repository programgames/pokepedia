<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PokemonSpecy
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
    private $pokemonSpeciesOrder;

    /**
     * @ORM\ManyToMany(targetEntity=EggGroup::class, inversedBy="pokemonSpecies")
     */
    private $eggGroups;

    /**
     * @ORM\OneToMany(targetEntity=Pokemon::class, mappedBy="pokemonSpecy", orphanRemoval=true)
     */
    private $pokemons;

    /**
     * @ORM\ManyToOne(targetEntity=EvolutionChain::class, inversedBy="pokemonSpecies")
     */
    private $evolutionChain;

    /**
     * @ORM\OneToMany(targetEntity=SpecyName::class, mappedBy="pokemonSpecy", orphanRemoval=true)
     */
    private $names;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="pokemonSpecies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

    public function __construct()
    {
        $this->eggGroups = new ArrayCollection();
        $this->pokemons = new ArrayCollection();
        $this->names = new ArrayCollection();
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

    public function getPokemonSpeciesOrder(): ?int
    {
        return $this->pokemonSpeciesOrder;
    }

    public function setPokemonSpeciesOrder(int $pokemonSpeciesOrder): self
    {
        $this->pokemonSpeciesOrder = $pokemonSpeciesOrder;

        return $this;
    }

    /**
     * @return Collection|EggGroup[]
     */
    public function getEggGroups(): Collection
    {
        return $this->eggGroups;
    }

    public function addEggGroup(EggGroup $eggGroup): self
    {
        if (!$this->eggGroups->contains($eggGroup)) {
            $this->eggGroups[] = $eggGroup;
        }

        return $this;
    }

    public function removeEggGroup(EggGroup $eggGroup): self
    {
        $this->eggGroups->removeElement($eggGroup);

        return $this;
    }

    /**
     * @return Collection|Pokemon[]
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons[] = $pokemon;
            $pokemon->setPokemonSpecy($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        if ($this->pokemons->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getPokemonSpecy() === $this) {
                $pokemon->setPokemonSpecy(null);
            }
        }

        return $this;
    }

    public function getEvolutionChain(): ?EvolutionChain
    {
        return $this->evolutionChain;
    }

    public function setEvolutionChain(?EvolutionChain $evolutionChain): self
    {
        $this->evolutionChain = $evolutionChain;

        return $this;
    }

    /**
     * @return Collection|SpecyName[]
     */
    public function getNames(): Collection
    {
        return $this->names;
    }

    public function addName(SpecyName $name): self
    {
        if (!$this->names->contains($name)) {
            $this->names[] = $name;
            $name->setPokemonSpecy($this);
        }

        return $this;
    }

    public function removeName(SpecyName $name): self
    {
        if ($this->names->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getPokemonSpecy() === $this) {
                $name->setPokemonSpecy(null);
            }
        }

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
}
