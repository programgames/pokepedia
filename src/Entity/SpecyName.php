<?php

namespace App\Entity;

use App\Repository\SpecyNameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpecyNameRepository::class)
 */
class SpecyName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $language;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonSpecy::class, inversedBy="names")
     * @ORM\JoinColumn(nullable=false)
     */
    private PokemonSpecy $pokemonSpecy;

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

    public function getLanguage(): ?int
    {
        return $this->language;
    }

    public function setLanguage(int $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getPokemonSpecy(): ?PokemonSpecy
    {
        return $this->pokemonSpecy;
    }

    public function setPokemonSpecy(PokemonSpecy $pokemonSpecy): self
    {
        $this->pokemonSpecy = $pokemonSpecy;

        return $this;
    }
}
