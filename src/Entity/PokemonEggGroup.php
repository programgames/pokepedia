<?php

namespace App\Entity;

use App\Repository\PokemonEggGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonEggGroupRepository::class)
 */
class PokemonEggGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=EggGroup::class, inversedBy="pokemonEggGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $eggGroup;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonSpecy::class, inversedBy="pokemonEggGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemonSpecy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEggGroup(): ?EggGroup
    {
        return $this->eggGroup;
    }

    public function setEggGroup(?EggGroup $eggGroup): self
    {
        $this->eggGroup = $eggGroup;

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
}
