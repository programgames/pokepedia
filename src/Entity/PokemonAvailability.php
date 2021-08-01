<?php

namespace App\Entity;

use App\Repository\PokemonAvailabilityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonAvailabilityRepository::class)
 */
class PokemonAvailability
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="pokemonAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Pokemon $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity=VersionGroup::class, inversedBy="pokemonAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?VersionGroup $versionGroup;

    /**
     * @ORM\Column(type="boolean", name="availability")
     */
    private bool $available = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getVersionGroup(): ?VersionGroup
    {
        return $this->versionGroup;
    }

    public function setVersionGroup(?VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;

        return $this;
    }

    public function isAvailable(): ?bool
    {
        return $this->available;
    }

    public function setAvailable(bool $available): self
    {
        $this->available = $available;

        return $this;
    }
}
