<?php

namespace App\Entity;

use App\Repository\PokemonFormRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonFormRepository::class)
 */
class PokemonForm
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
    private $formName;

    /**
     * @ORM\Column(type="integer")
     */
    private $formOrder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBattleOnly;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isDefault;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="pokemonForms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemon;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMega;

    /**
     * @ORM\OneToMany(targetEntity=PokemonFormGeneration::class, mappedBy="pokemonForm")
     */
    private $pokemonFormGenerations;

    /**
     * @ORM\ManyToOne(targetEntity=VersionGroup::class, inversedBy="pokemonForms")
     * @ORM\JoinColumn(nullable=false)
     */
    private $versionGroup;

    /**
     * @ORM\OneToMany(targetEntity=PokemonFormName::class, mappedBy="pokemonForm")
     */
    private $pokemonFormNames;

    public function __construct()
    {
        $this->pokemonFormGenerations = new ArrayCollection();
        $this->pokemonFormNames = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormName(): ?string
    {
        return $this->formName;
    }

    public function setFormName(string $formName): self
    {
        $this->formName = $formName;

        return $this;
    }

    public function getFormOrder(): ?int
    {
        return $this->formOrder;
    }

    public function setFormOrder(int $formOrder): self
    {
        $this->formOrder = $formOrder;

        return $this;
    }

    public function getIsBattleOnly(): ?bool
    {
        return $this->isBattleOnly;
    }

    public function setIsBattleOnly(bool $isBattleOnly): self
    {
        $this->isBattleOnly = $isBattleOnly;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    public function getIsMega(): ?bool
    {
        return $this->isMega;
    }

    public function setIsMega(bool $isMega): self
    {
        $this->isMega = $isMega;

        return $this;
    }

    /**
     * @return Collection|PokemonFormGeneration[]
     */
    public function getPokemonFormGenerations(): Collection
    {
        return $this->pokemonFormGenerations;
    }

    public function addPokemonFormGeneration(PokemonFormGeneration $pokemonFormGeneration): self
    {
        if (!$this->pokemonFormGenerations->contains($pokemonFormGeneration)) {
            $this->pokemonFormGenerations[] = $pokemonFormGeneration;
            $pokemonFormGeneration->setPokemonForm($this);
        }

        return $this;
    }

    public function removePokemonFormGeneration(PokemonFormGeneration $pokemonFormGeneration): self
    {
        if ($this->pokemonFormGenerations->removeElement($pokemonFormGeneration)) {
            // set the owning side to null (unless already changed)
            if ($pokemonFormGeneration->getPokemonForm() === $this) {
                $pokemonFormGeneration->setPokemonForm(null);
            }
        }

        return $this;
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

    /**
     * @return Collection|PokemonFormName[]
     */
    public function getPokemonFormNames(): Collection
    {
        return $this->pokemonFormNames;
    }

    public function addPokemonFormName(PokemonFormName $pokemonFormName): self
    {
        if (!$this->pokemonFormNames->contains($pokemonFormName)) {
            $this->pokemonFormNames[] = $pokemonFormName;
            $pokemonFormName->setPokemonForm($this);
        }

        return $this;
    }

    public function removePokemonFormName(PokemonFormName $pokemonFormName): self
    {
        if ($this->pokemonFormNames->removeElement($pokemonFormName)) {
            // set the owning side to null (unless already changed)
            if ($pokemonFormName->getPokemonForm() === $this) {
                $pokemonFormName->setPokemonForm(null);
            }
        }

        return $this;
    }
}
