<?php

namespace App\Entity;

use App\Repository\PokemonFormNameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonFormNameRepository::class)
 */
class PokemonFormName
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
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $language;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pokemonName;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonForm::class, inversedBy="pokemonFormNames")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemonForm;

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

    public function getPokemonName(): ?string
    {
        return $this->pokemonName;
    }

    public function setPokemonName(string $pokemonName): self
    {
        $this->pokemonName = $pokemonName;

        return $this;
    }

    public function getPokemonForm(): PokemonForm
    {
        return $this->pokemonForm;
    }

    public function setPokemonForm(?PokemonForm $pokemonForm): self
    {
        $this->pokemonForm = $pokemonForm;

        return $this;
    }
}
