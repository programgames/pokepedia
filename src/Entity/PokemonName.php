<?php

namespace App\Entity;

use App\Repository\PokemonNameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * @ORM\Entity(repositoryClass=PokemonNameRepository::class)
 * @Table(name="pokemon_name")
 */
class PokemonName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer", name="language_id")
     */
    private ?int $languageId;

    /**
     * @ORM\Column(type="string", length=255, name="name")
     */
    private ?string $name;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="names")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Pokemon $pokemon;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function setLanguageId(int $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
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
