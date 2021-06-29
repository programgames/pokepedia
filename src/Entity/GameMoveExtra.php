<?php

namespace App\Entity;

use App\Repository\GameMoveExtraRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameMoveExtraRepository::class)
 */
class GameMoveExtra
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $startAt;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $price;

    /**
     * @ORM\ManyToOne(targetEntity=Move::class, inversedBy="gameExtras")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Move $move;

    /**
     * @ORM\ManyToOne(targetEntity=Game::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?game $game;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?int
    {
        return $this->startAt;
    }

    public function setStartAt(int $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getMove(): ?Move
    {
        return $this->move;
    }

    public function setMove(?Move $move): self
    {
        $this->move = $move;

        return $this;
    }

    public function getGame(): ?game
    {
        return $this->game;
    }

    public function setGame(?game $game): self
    {
        $this->game = $game;

        return $this;
    }
}
