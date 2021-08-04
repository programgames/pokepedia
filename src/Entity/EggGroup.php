<?php

namespace App\Entity;

use App\Repository\EggGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EggGroupRepository::class)
 */
class EggGroup
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
     * @ORM\ManyToMany(targetEntity=PokemonSpecy::class, mappedBy="eggGroups")
     */
    private Collection $pokemonSpecies;

    /**
     * @ORM\OneToMany(targetEntity=PokemonEggGroup::class, mappedBy="eggGroup")
     */
    private $pokemonEggGroups;

    public function __construct()
    {
        $this->pokemonSpecies = new ArrayCollection();
        $this->pokemonEggGroups = new ArrayCollection();
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
            $pokemonSpecies->addEggGroup($this);
        }

        return $this;
    }

    public function removePokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        if ($this->pokemonSpecies->removeElement($pokemonSpecies)) {
            $pokemonSpecies->removeEggGroup($this);
        }

        return $this;
    }

    /**
     * @return Collection|PokemonEggGroup[]
     */
    public function getPokemonEggGroups(): Collection
    {
        return $this->pokemonEggGroups;
    }

    public function addPokemonEggGroup(PokemonEggGroup $pokemonEggGroup): self
    {
        if (!$this->pokemonEggGroups->contains($pokemonEggGroup)) {
            $this->pokemonEggGroups[] = $pokemonEggGroup;
            $pokemonEggGroup->setEggGroup($this);
        }

        return $this;
    }

    public function removePokemonEggGroup(PokemonEggGroup $pokemonEggGroup): self
    {
        if ($this->pokemonEggGroups->removeElement($pokemonEggGroup)) {
            // set the owning side to null (unless already changed)
            if ($pokemonEggGroup->getEggGroup() === $this) {
                $pokemonEggGroup->setEggGroup(null);
            }
        }

        return $this;
    }
}
