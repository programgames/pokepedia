<?php

namespace App\Entity;

use App\Repository\PokemonFormGenerationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonFormGenerationRepository::class)
 */
class PokemonFormGeneration
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
     * @ORM\ManyToOne(targetEntity=Generation::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonForm::class, inversedBy="pokemonFormGenerations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemonForm;

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

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getPokemonForm(): ?PokemonForm
    {
        return $this->pokemonForm;
    }

    public function setPokemonForm(?PokemonForm $pokemonForm): self
    {
        $this->pokemonForm = $pokemonForm;

        return $this;
    }
}
