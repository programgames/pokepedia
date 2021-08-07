<?php

namespace App\Entity;

use App\Repository\PokedexRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokedexRepository::class)
 */
class Pokedex
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
     * @ORM\Column(type="boolean")
     */
    private $isMainSeries;

    /**
     * @ORM\OneToMany(targetEntity=PokedexVersionGroup::class, mappedBy="pokedex")
     */
    private $pokedexVersionGroups;

    /**
     * @ORM\OneToMany(targetEntity=PokemonDexNumber::class, mappedBy="pokedex")
     */
    private $pokemonDexNumbers;

    /**
     * @ORM\ManyToOne(targetEntity=Region::class, inversedBy="pokedexes")
     * @ORM\JoinColumn(nullable=true)
     */
    private $region;

    public function __construct()
    {
        $this->pokedexVersionGroups = new ArrayCollection();
        $this->pokemonDexNumbers = new ArrayCollection();
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

    public function getIsMainSeries(): ?bool
    {
        return $this->isMainSeries;
    }

    public function setIsMainSeries(bool $isMainSeries): self
    {
        $this->isMainSeries = $isMainSeries;

        return $this;
    }

    /**
     * @return Collection|PokedexVersionGroup[]
     */
    public function getPokedexVersionGroups(): Collection
    {
        return $this->pokedexVersionGroups;
    }

    public function addPokedexVersionGroup(PokedexVersionGroup $pokedexVersionGroup): self
    {
        if (!$this->pokedexVersionGroups->contains($pokedexVersionGroup)) {
            $this->pokedexVersionGroups[] = $pokedexVersionGroup;
            $pokedexVersionGroup->setPokedex($this);
        }

        return $this;
    }

    public function removePokedexVersionGroup(PokedexVersionGroup $pokedexVersionGroup): self
    {
        if ($this->pokedexVersionGroups->removeElement($pokedexVersionGroup)) {
            // set the owning side to null (unless already changed)
            if ($pokedexVersionGroup->getPokedex() === $this) {
                $pokedexVersionGroup->setPokedex(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonDexNumber[]
     */
    public function getPokemonDexNumbers(): Collection
    {
        return $this->pokemonDexNumbers;
    }

    public function addPokemonDexNumber(PokemonDexNumber $pokemonDexNumber): self
    {
        if (!$this->pokemonDexNumbers->contains($pokemonDexNumber)) {
            $this->pokemonDexNumbers[] = $pokemonDexNumber;
            $pokemonDexNumber->setPokedex($this);
        }

        return $this;
    }

    public function removePokemonDexNumber(PokemonDexNumber $pokemonDexNumber): self
    {
        if ($this->pokemonDexNumbers->removeElement($pokemonDexNumber)) {
            // set the owning side to null (unless already changed)
            if ($pokemonDexNumber->getPokedex() === $this) {
                $pokemonDexNumber->setPokedex(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
