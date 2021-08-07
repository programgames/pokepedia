<?php

namespace App\Entity;

use App\Repository\GenerationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenerationRepository::class)
 */
class Generation
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
     * @ORM\OneToMany(targetEntity=VersionGroup::class, mappedBy="generation", orphanRemoval=true)
     */
    private Collection $versionGroups;

    /**
     * @ORM\OneToMany(targetEntity=PokemonSpecy::class, mappedBy="generation", orphanRemoval=true)
     */
    private Collection $pokemonSpecies;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private int $generationIdentifier;

    /**
     * @ORM\OneToMany(targetEntity=Type::class, mappedBy="generation")
     */
    private $types;

    /**
     * @ORM\OneToMany(targetEntity=PokemonTypePast::class, mappedBy="generation")
     */
    private $pokemonTypePasts;

    /**
     * @ORM\OneToMany(targetEntity=Move::class, mappedBy="generation")
     */
    private $moves;

    /**
     * @ORM\OneToOne(targetEntity=Region::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    public function __construct()
    {
        $this->versionGroups = new ArrayCollection();
        $this->pokemonSpecies = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->pokemonTypePasts = new ArrayCollection();
        $this->moves = new ArrayCollection();
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
     * @return Collection|VersionGroup[]
     */
    public function getVersionGroups(): Collection
    {
        return $this->versionGroups;
    }

    public function addVersionGroup(VersionGroup $versionGroup): self
    {
        if (!$this->versionGroups->contains($versionGroup)) {
            $this->versionGroups[] = $versionGroup;
            $versionGroup->setGeneration($this);
        }

        return $this;
    }

    public function removeVersionGroup(VersionGroup $versionGroup): self
    {
        if ($this->versionGroups->removeElement($versionGroup)) {
            // set the owning side to null (unless already changed)
            if ($versionGroup->getGeneration() === $this) {
                $versionGroup->setGeneration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonSpecy[]
     */
    public function getPokemonSpecies(): Collection
    {
        return $this->pokemonSpecies;
    }

    public function addPokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        if (!$this->pokemonSpecies->contains($pokemonSpecies)) {
            $this->pokemonSpecies[] = $pokemonSpecies;
            $pokemonSpecies->setGeneration($this);
        }

        return $this;
    }

    public function removePokemonSpecies(PokemonSpecy $pokemonSpecies): self
    {
        if ($this->pokemonSpecies->removeElement($pokemonSpecies)) {
            // set the owning side to null (unless already changed)
            if ($pokemonSpecies->getGeneration() === $this) {
                $pokemonSpecies->setGeneration(null);
            }
        }

        return $this;
    }

    public function getGenerationIdentifier(): ?int
    {
        return $this->generationIdentifier;
    }

    public function setGenerationIdentifier(int $generationIdentifier): self
    {
        $this->generationIdentifier = $generationIdentifier;

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->setGeneration($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getGeneration() === $this) {
                $type->setGeneration(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonTypePast[]
     */
    public function getPokemonTypePasts(): Collection
    {
        return $this->pokemonTypePasts;
    }

    public function addPokemonTypePast(PokemonTypePast $pokemonTypePast): self
    {
        if (!$this->pokemonTypePasts->contains($pokemonTypePast)) {
            $this->pokemonTypePasts[] = $pokemonTypePast;
            $pokemonTypePast->setGeneration($this);
        }

        return $this;
    }

    public function removePokemonTypePast(PokemonTypePast $pokemonTypePast): self
    {
        if ($this->pokemonTypePasts->removeElement($pokemonTypePast)) {
            // set the owning side to null (unless already changed)
            if ($pokemonTypePast->getGeneration() === $this) {
                $pokemonTypePast->setGeneration(null);
            }
        }

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
            $move->setGeneration($this);
        }

        return $this;
    }

    public function removeMove(Move $move): self
    {
        if ($this->moves->removeElement($move)) {
            // set the owning side to null (unless already changed)
            if ($move->getGeneration() === $this) {
                $move->setGeneration(null);
            }
        }

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(Region $region): self
    {
        $this->region = $region;

        return $this;
    }
}
