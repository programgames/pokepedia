<?php

namespace App\Entity;

use App\Repository\PokemonGameIndexRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonGameIndexRepository::class)
 */
class PokemonGameIndex
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $gameIndex;

    /**
     * @ORM\ManyToOne(targetEntity=Version::class, inversedBy="pokemonGameIndices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="pokemonGameIndices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGameIndex(): ?int
    {
        return $this->gameIndex;
    }

    public function setGameIndex(int $gameIndex): self
    {
        $this->gameIndex = $gameIndex;

        return $this;
    }

    public function getVersion(): ?Version
    {
        return $this->version;
    }

    public function setVersion(?Version $version): self
    {
        $this->version = $version;

        return $this;
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
}
