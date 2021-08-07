<?php

namespace App\Entity;

use App\Repository\PokemonMoveAvailabilityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonMoveAvailabilityRepository::class)
 */
class PokemonMoveAvailability
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=VersionGroup::class, inversedBy="pokemonMoveAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $versionGroup;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="pokemonMoveAvailabilities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemon;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDefault = true;

    /**
     * @ORM\ManyToMany(targetEntity=Pokemon::class, inversedBy="pokemonMoveAvailabilities")
     */
    private $moveForms;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasCustomPokepediaPage = true;

    public function __construct()
    {
        $this->moveForms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersionGroup(): ?VersionGroup
    {
        return $this->versionGroup;
    }

    public function setVersionGroup(?VersionGroup $versionGroup): self
    {
        $this->versionGroup = $versionGroup;

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

    public function getIsDefault(): ?bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return Collection|Pokemon[]
     */
    public function getMoveForms(): Collection
    {
        return $this->moveForms;
    }

    public function addMoveForm(Pokemon $moveForm): self
    {
        if (!$this->moveForms->contains($moveForm)) {
            $this->moveForms[] = $moveForm;
        }

        return $this;
    }

    public function removeMoveForm(Pokemon $moveForm): self
    {
        $this->moveForms->removeElement($moveForm);

        return $this;
    }

    public function getHasCustomPokepediaPage(): ?bool
    {
        return $this->hasCustomPokepediaPage;
    }

    public function setHasCustomPokepediaPage(bool $hasCustomPokepediaPage): self
    {
        $this->hasCustomPokepediaPage = $hasCustomPokepediaPage;

        return $this;
    }
}
