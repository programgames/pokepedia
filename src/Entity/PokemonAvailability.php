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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="pokemonAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemon;

    /**
     * @ORM\ManyToOne(targetEntity=Versiongroup::class, inversedBy="pokemonAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $versionGroup;

    /**
     * @ORM\Column(type="boolean", name="availability")
     */
    private $available;

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

    public function getVersionGroup(): ?Versiongroup
    {
        return $this->versionGroup;
    }

    public function setVersionGroup(?Versiongroup $versionGroup): self
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
