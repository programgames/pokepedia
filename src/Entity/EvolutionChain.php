<?php

namespace App\Entity;

use App\Repository\EvolutionChainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EvolutionChainRepository::class)
 */
class EvolutionChain
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\OneToMany(targetEntity=PokemonSpecy::class, mappedBy="evolutionChain")
     */
    private Collection $pokemonSpecies;

    public function __construct()
    {
        $this->pokemonSpecies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|PokemonSpecy[]
     */
    public function getPokemonSpecies(): Collection
    {
        return $this->pokemonSpecies;
    }

    public function addPokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        if (!$this->pokemonSpecies->contains($pokemonSpecies)) {
            $this->pokemonSpecies[] = $pokemonSpecies;
            $pokemonSpecies->setEvolutionChain($this);
        }

        return $this;
    }

    public function removePokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        // set the owning side to null (unless already changed)
        if ($this->pokemonSpecies->removeElement($pokemonSpecies) && $pokemonSpecies->getEvolutionChain() === $this) {
            $pokemonSpecies->setEvolutionChain(null);
        }

        return $this;
    }
}
