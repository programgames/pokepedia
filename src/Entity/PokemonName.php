<?php

namespace App\Entity;

use App\Repository\PokemonNameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonNameRepository::class)
 * @ORM\Table(
 *    name="pokemon_name",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uniq_pokemon_hame", columns={"language_id", "name"})
 *    }
 * )
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
