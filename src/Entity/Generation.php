<?php

namespace App\Entity;

use App\Repository\GenerationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenerationRepository::class)
 */
class Generation
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
     * @ORM\OneToMany(targetEntity=VersionGroup::class, mappedBy="generation", orphanRemoval=true)
     */
    private Collection $versionGroups;

    /**
     * @ORM\OneToMany(targetEntity=PokemonSpecy::class, mappedBy="generation", orphanRemoval=true)
     */
    private Collection $pokemonSpecies;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $generationIdentifier;

    public function __construct()
    {
        $this->versionGroups = new ArrayCollection();
        $this->pokemonSpecies = new ArrayCollection();
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
     * @return Collection|VersionGroup[]
     */
    public function getVersionGroups(): Collection
    {
        return $this->versionGroups;
    }

    public function addVersionGroup(VersionGroup $versionGroup): self
    {
        if (!$this->versionGroups->contains($versionGroup)) {
            $this->versionGroups[] = $versionGroup;
            $versionGroup->setGeneration($this);
        }

        return $this;
    }

    public function removeVersionGroup(VersionGroup $versionGroup): self
    {
        if ($this->versionGroups->removeElement($versionGroup)) {
            // set the owning side to null (unless already changed)
            if ($versionGroup->getGeneration() === $this) {
                $versionGroup->setGeneration(null);
            }
        }

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
            $pokemonSpecies->setGeneration($this);
        }

        return $this;
    }

    public function removePokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        if ($this->pokemonSpecies->removeElement($pokemonSpecies)) {
            // set the owning side to null (unless already changed)
            if ($pokemonSpecies->getGeneration() === $this) {
                $pokemonSpecies->setGeneration(null);
            }
        }

        return $this;
    }

    public function getGenerationIdentifier(): ?int
    {
        return $this->generationIdentifier;
    }

    public function setGenerationIdentifier(?int $generationIdentifier): self
    {
        $this->generationIdentifier = $generationIdentifier;

        return $this;
    }
}
