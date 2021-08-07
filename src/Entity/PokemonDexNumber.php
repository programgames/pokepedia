<?php

namespace App\Entity;

use App\Repository\PokemonDexNumberRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonDexNumberRepository::class)
 */
class PokemonDexNumber
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=false)
     */
    private $pokedexNumber;

    /**
     * @ORM\ManyToOne(targetEntity=Pokedex::class, inversedBy="pokemonDexNumbers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokedex;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonSpecy::class, inversedBy="pokemonDexNumbers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemonSpecy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokedexNumber(): int
    {
        return $this->pokedexNumber;
    }

    public function setPokedexNumber(int $pokedexNumber): self
    {
        $this->pokedexNumber = $pokedexNumber;

        return $this;
    }

    public function getPokedex(): Pokedex
    {
        return $this->pokedex;
    }

    public function setPokedex(Pokedex $pokedex): self
    {
        $this->pokedex = $pokedex;

        return $this;
    }

    public function getPokemonSpecy(): PokemonSpecy
    {
        return $this->pokemonSpecy;
    }

    public function setPokemonSpecy(PokemonSpecy $pokemonSpecy): self
    {
        $this->pokemonSpecy = $pokemonSpecy;

        return $this;
    }
}
