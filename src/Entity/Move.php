<?php

namespace App\Entity;

use App\Repository\MoveRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoveRepository::class)
 */
class Move
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class, inversedBy="moves")
     */
    private $pokemon;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $learningType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $englishName;

    /**
     * @ORM\Column(type="integer")
     */
    private $generation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $games;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $form;

    public function __construct()
    {
        $this->pokemon = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Pokemon[]
     */
    public function getPokemon(): Collection
    {
        return $this->pokemon;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemon->contains($pokemon)) {
            $this->pokemon[] = $pokemon;
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        $this->pokemon->removeElement($pokemon);

        return $this;
    }

    public function getLearningType(): ?string
    {
        return $this->learningType;
    }

    public function setLearningType(string $learningType): self
    {
        $this->learningType = $learningType;

        return $this;
    }

    public function getEnglishName(): ?string
    {
        return $this->englishName;
    }

    public function setEnglishName(string $englishName): self
    {
        $this->englishName = $englishName;

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

    public function getGames(): ?string
    {
        return $this->games;
    }

    public function setGames(?string $games): self
    {
        $this->games = $games;

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
}
