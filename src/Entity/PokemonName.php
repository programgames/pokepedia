<?php

namespace App\Entity;

use App\Repository\PokemonNameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonNameRepository::class)
 */
class PokemonName
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
    private $bulbapediaName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pokepediaName;

    /**
     * @ORM\OneToOne(targetEntity=Pokemon::class, mappedBy="pokemonName", cascade={"persist", "remove"})
     */
    private $pokemon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBulbapediaName(): ?string
    {
        return $this->bulbapediaName;
    }

    public function setBulbapediaName(string $bulbapediaName): self
    {
        $this->bulbapediaName = $bulbapediaName;

        return $this;
    }

    public function getPokepediaName(): ?string
    {
        return $this->pokepediaName;
    }

    public function setPokepediaName(string $pokepediaName): self
    {
        $this->pokepediaName = $pokepediaName;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        // unset the owning side of the relation if necessary
        if ($pokemon === null && $this->pokemon !== null) {
            $this->pokemon->setPokemonName(null);
        }

        // set the owning side of the relation if necessary
        if ($pokemon !== null && $pokemon->getPokemonName() !== $this) {
            $pokemon->setPokemonName($this);
        }

        $this->pokemon = $pokemon;

        return $this;
    }
}
