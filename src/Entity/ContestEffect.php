<?php

namespace App\Entity;

use App\Repository\ContestEffectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContestEffectRepository::class)
 */
class ContestEffect
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
    private $appeal;

    /**
     * @ORM\Column(type="integer")
     */
    private $jam;

    /**
     * @ORM\OneToMany(targetEntity=Move::class, mappedBy="contestEffect")
     */
    private $moves;

    public function __construct()
    {
        $this->moves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppeal(): ?int
    {
        return $this->appeal;
    }

    public function setAppeal(int $appeal): self
    {
        $this->appeal = $appeal;

        return $this;
    }

    public function getJam(): ?int
    {
        return $this->jam;
    }

    public function setJam(int $jam): self
    {
        $this->jam = $jam;

        return $this;
    }

    /**
     * @return Collection|Move[]
     */
    public function getMoves(): Collection
    {
        return $this->moves;
    }

    public function addMove(Move $move): self
    {
        if (!$this->moves->contains($move)) {
            $this->moves[] = $move;
            $move->setContestEffect($this);
        }

        return $this;
    }

    public function removeMove(Move $move): self
    {
        if ($this->moves->removeElement($move)) {
            // set the owning side to null (unless already changed)
            if ($move->getContestEffect() === $this) {
                $move->setContestEffect(null);
            }
        }

        return $this;
    }
}
