<?php

namespace App\Entity;

use App\Repository\PokemonNameRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
/**
 * @ORM\Entity(repositoryClass=PokemonNameRepository::class)
 * @Table(name="pokemon_name",uniqueConstraints={@UniqueConstraint(name="trad", columns={"species_id", "language_id"})})
 */
class PokemonName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="species_id")
     */
    private $speciesId;

    /**
     * @ORM\Column(type="integer", name="language_id")
     */
    private $languageId;

    /**
     * @ORM\Column(type="string", length=255, name="name")
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpeciesId(): ?int
    {
        return $this->speciesId;
    }

    public function setSpeciesId(int $speciesId): self
    {
        $this->speciesId = $speciesId;

        return $this;
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
}
