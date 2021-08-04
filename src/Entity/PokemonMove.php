<?php

namespace App\Entity;

use App\Repository\PokemonMoveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonMoveRepository::class)
 */
class PokemonMove
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $level;

    /**
     * @ORM\ManyToOne(targetEntity=Move::class, inversedBy="pokemonMoves")
     * @ORM\JoinColumn(nullable=false)
     */
    private Move $move;

    /**
     * @ORM\ManyToOne(targetEntity=VersionGroup::class, inversedBy="pokemonMoves")
     * @ORM\JoinColumn(nullable=false)
     */
    private VersionGroup $versionGroup;

    /**
     * @ORM\ManyToOne(targetEntity=MoveLearnMethod::class, inversedBy="pokemonMoves")
     * @ORM\JoinColumn(nullable=false)
     */
    private MoveLearnMethod $learnMethod;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="pokemonMoves")
     * @ORM\JoinColumn(nullable=false)
     */
    private pokemon $pokemon;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pokemonMoveOrder;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getMove(): ?Move
    {
        return $this->move;
    }

    public function setMove(Move $move): self
    {
        $this->move = $move;

        return $this;
    }

    public function getVersionGroup(): ?VersionGroup
    {
        return $this->versionGroup;
    }

    public function setVersionGroup(VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;

        return $this;
    }

    public function getLearnMethod(): MoveLearnMethod
    {
        return $this->learnMethod;
    }

    public function setLearnMethod(?MoveLearnMethod $learnMethod): self
    {
        $this->learnMethod = $learnMethod;

        return $this;
    }

    public function getPokemon(): ?pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;

        return $this;
    }

    public function getPokemonMoveOrder(): ?int
    {
        return $this->pokemonMoveOrder;
    }

    public function setPokemonMoveOrder(?int $pokemonMoveOrder): self
    {
        $this->pokemonMoveOrder = $pokemonMoveOrder;

        return $this;
    }
}
