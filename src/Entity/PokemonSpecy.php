<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class PokemonSpecy
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
     * @ORM\OneToMany(targetEntity=Pokemon::class, mappedBy="pokemonSpecy", orphanRemoval=true)
     */
    private Collection $pokemons;

    /**
     * @ORM\ManyToOne(targetEntity=EvolutionChain::class, inversedBy="pokemonSpecies")
     */
    private ?EvolutionChain $evolutionChain;

    /**
     * @ORM\OneToMany(targetEntity=SpecyName::class, mappedBy="pokemonSpecy", orphanRemoval=true)
     */
    private Collection $names;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="pokemonSpecies")
     * @ORM\JoinColumn(nullable=false)
     */
    private Generation $generation;

    /**
     * @ORM\Column(type="integer")
     */
    private $baseHapiness;

    /**
     * @ORM\Column(type="integer")
     */
    private $captureRate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $formsSwitchable;

    /**
     * @ORM\Column(type="integer")
     */
    private $genderRate;

    /**
     * @ORM\ManyToOne(targetEntity=GrowthRate::class, inversedBy="pokemonSpecies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $growthRate;

    /**
     * @ORM\Column(type="boolean")
     */
    private $hasGenderDifferences;

    /**
     * @ORM\Column(type="integer")
     */
    private $hatchCounter;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isBaby;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isLegendary;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isMythical;

    /**
     * @ORM\Column(type="integer")
     */
    private $pokemonSpecyOrder;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonShape::class, inversedBy="pokemonSpecies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemonShape;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonHabitat::class, inversedBy="pokemonSpecies")
     */
    private $pokemonHabitat;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonColor::class, inversedBy="pokemonSpecies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokemonColor;

    /**
     * @ORM\OneToMany(targetEntity=PokemonEggGroup::class, mappedBy="pokemonSpecy")
     */
    private $pokemonEggGroups;

    /**
     * @ORM\ManyToOne(targetEntity=PokemonSpecy::class)
     */
    private $evolveFrom;

    public function __construct()
    {
        $this->pokemons = new ArrayCollection();
        $this->names = new ArrayCollection();
        $this->pokemonEggGroups = new ArrayCollection();
    }

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

    /**
     * @return Collection|Pokemon[]
     */
    public function getPokemons(): Collection
    {
        return $this->pokemons;
    }

    public function addPokemon(Pokemon $pokemon): self
    {
        if (!$this->pokemons->contains($pokemon)) {
            $this->pokemons[] = $pokemon;
            $pokemon->setPokemonSpecy($this);
        }

        return $this;
    }

    public function removePokemon(Pokemon $pokemon): self
    {
        if ($this->pokemons->removeElement($pokemon)) {
            // set the owning side to null (unless already changed)
            if ($pokemon->getPokemonSpecy() === $this) {
                $pokemon->setPokemonSpecy(null);
            }
        }

        return $this;
    }

    public function getEvolutionChain(): ?EvolutionChain
    {
        return $this->evolutionChain;
    }

    public function setEvolutionChain(?EvolutionChain $evolutionChain): self
    {
        $this->evolutionChain = $evolutionChain;

        return $this;
    }

    /**
     * @return Collection|SpecyName[]
     */
    public function getNames(): Collection
    {
        return $this->names;
    }

    public function addName(SpecyName $name): self
    {
        if (!$this->names->contains($name)) {
            $this->names[] = $name;
            $name->setPokemonSpecy($this);
        }

        return $this;
    }

    public function removeName(SpecyName $name): self
    {
        if ($this->names->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getPokemonSpecy() === $this) {
                $name->setPokemonSpecy(null);
            }
        }

        return $this;
    }

    public function getGeneration(): ?Generation
    {
        return $this->generation;
    }

    public function setGeneration(Generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    public function getBaseHapiness(): ?int
    {
        return $this->baseHapiness;
    }

    public function setBaseHapiness(int $baseHapiness): self
    {
        $this->baseHapiness = $baseHapiness;

        return $this;
    }

    public function getCaptureRate(): ?int
    {
        return $this->captureRate;
    }

    public function setCaptureRate(int $captureRate): self
    {
        $this->captureRate = $captureRate;

        return $this;
    }

    public function getFormsSwitchable(): ?bool
    {
        return $this->formsSwitchable;
    }

    public function setFormsSwitchable(bool $formsSwitchable): self
    {
        $this->formsSwitchable = $formsSwitchable;

        return $this;
    }

    public function getGenderRate(): ?int
    {
        return $this->genderRate;
    }

    public function setGenderRate(int $genderRate): self
    {
        $this->genderRate = $genderRate;

        return $this;
    }

    public function getGrowthRate(): ?GrowthRate
    {
        return $this->growthRate;
    }

    public function setGrowthRate(?GrowthRate $growthRate): self
    {
        $this->growthRate = $growthRate;

        return $this;
    }

    public function getHasGenderDifferences(): ?bool
    {
        return $this->hasGenderDifferences;
    }

    public function setHasGenderDifferences(bool $hasGenderDifferences): self
    {
        $this->hasGenderDifferences = $hasGenderDifferences;

        return $this;
    }

    public function getHatchCounter(): ?int
    {
        return $this->hatchCounter;
    }

    public function setHatchCounter(int $hatchCounter): self
    {
        $this->hatchCounter = $hatchCounter;

        return $this;
    }

    public function getIsBaby(): ?bool
    {
        return $this->isBaby;
    }

    public function setIsBaby(bool $isBaby): self
    {
        $this->isBaby = $isBaby;

        return $this;
    }

    public function getIsLegendary(): ?bool
    {
        return $this->isLegendary;
    }

    public function setIsLegendary(bool $isLegendary): self
    {
        $this->isLegendary = $isLegendary;

        return $this;
    }

    public function getIsMythical(): ?bool
    {
        return $this->isMythical;
    }

    public function setIsMythical(bool $isMythical): self
    {
        $this->isMythical = $isMythical;

        return $this;
    }

    public function getPokemonSpecyOrder(): ?int
    {
        return $this->pokemonSpecyOrder;
    }

    public function setPokemonSpecyOrder(int $pokemonSpecyOrder): self
    {
        $this->pokemonSpecyOrder = $pokemonSpecyOrder;

        return $this;
    }

    public function getPokemonShape(): ?PokemonShape
    {
        return $this->pokemonShape;
    }

    public function setPokemonShape(?PokemonShape $pokemonShape): self
    {
        $this->pokemonShape = $pokemonShape;

        return $this;
    }

    public function getPokemonHabitat(): ?PokemonHabitat
    {
        return $this->pokemonHabitat;
    }

    public function setPokemonHabitat(?PokemonHabitat $pokemonHabitat): self
    {
        $this->pokemonHabitat = $pokemonHabitat;

        return $this;
    }

    public function getPokemonColor(): ?PokemonColor
    {
        return $this->pokemonColor;
    }

    public function setPokemonColor(?PokemonColor $pokemonColor): self
    {
        $this->pokemonColor = $pokemonColor;

        return $this;
    }

    /**
     * @return Collection|PokemonEggGroup[]
     */
    public function getPokemonEggGroups(): Collection
    {
        return $this->pokemonEggGroups;
    }

    public function addPokemonEggGroup(PokemonEggGroup $pokemonEggGroup): self
    {
        if (!$this->pokemonEggGroups->contains($pokemonEggGroup)) {
            $this->pokemonEggGroups[] = $pokemonEggGroup;
            $pokemonEggGroup->setPokemonSpecy($this);
        }

        return $this;
    }

    public function removePokemonEggGroup(PokemonEggGroup $pokemonEggGroup): self
    {
        if ($this->pokemonEggGroups->removeElement($pokemonEggGroup)) {
            // set the owning side to null (unless already changed)
            if ($pokemonEggGroup->getPokemonSpecy() === $this) {
                $pokemonEggGroup->setPokemonSpecy(null);
            }
        }

        return $this;
    }

    public function getEvolveFrom(): ?self
    {
        return $this->evolveFrom;
    }

    public function setEvolveFrom(?self $evolveFrom): self
    {
        $this->evolveFrom = $evolveFrom;

        return $this;
    }
}
