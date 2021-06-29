<?php

namespace App\Entity;

use App\Repository\LevelingUpMoveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LevelingUpMoveRepository::class)
 * @ORM\Table(
 *    name="leveling_up_move",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uniq_move", columns={"move","pokemon_id","type","generation","form"})
 *    }
 * )
 */
class LevelingUpMove
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="move")
     */
    private $move;

    /**
     * @ORM\Column(type="integer", nullable=true, name="level")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255, name="type")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, name="attack_type")
     */
    private $attackType;

    /**
     * @ORM\Column(type="string", length=255, name="category")
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true, name="power")
     */
    private $power;

    /**
     * @ORM\Column(type="integer", nullable=true, name="accuracy")
     */
    private $accuracy;

    /**
     * @ORM\Column(type="integer", name="power_points")
     */
    private $powerPoints;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="form")
     */
    private $form;

    /**
     * @ORM\ManyToMany(targetEntity=Game::class)
     */
    private $games;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="levelingUpMoves")
     * @ORM\JoinColumn(nullable=false, name="pokemon_id")
     */
    private $pokemon;

    /**
     * @ORM\Column(type="integer", name="generation")
     */
    private $generation;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMove(): ?string
    {
        return $this->move;
    }

    public function setMove(string $move): self
    {
        $this->move = $move;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAttackType(): ?string
    {
        return $this->attackType;
    }

    public function setAttackType(string $attackType): self
    {
        $this->attackType = $attackType;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy(?int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getPowerPoints(): ?int
    {
        return $this->powerPoints;
    }

    public function setPowerPoints(int $powerPoints): self
    {
        $this->powerPoints = $powerPoints;

        return $this;
    }

    public function getForm(): ?string
    {
        return $this->form;
    }

    public function setForm(?string $form): self
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return Collection|Game[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        $this->games->removeElement($game);

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

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): self
    {
        $this->generation = $generation;

        return $this;
    }
}
