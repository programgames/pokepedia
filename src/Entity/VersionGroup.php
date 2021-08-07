<?php

namespace App\Entity;

use App\Repository\VersionGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VersionGroupRepository::class)
 */
class VersionGroup
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
     * @ORM\Column(type="integer")
     */
    private ?int $versionGroupOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="versionGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private Generation $generation;

    /**
     * @ORM\OneToMany(targetEntity=Machine::class, mappedBy="versionGroup", orphanRemoval=true)
     */
    private Collection $machines;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMove::class, mappedBy="versionGroup", orphanRemoval=true)
     */
    private Collection $pokemonMoves;

    /**
     * @ORM\OneToMany(targetEntity=PokedexVersionGroup::class, mappedBy="versionGroup")
     */
    private $pokedexVersionGroups;

    /**
     * @ORM\OneToMany(targetEntity=PokemonFormGeneration::class, mappedBy="versionGroup")
     */
    private $pokemonFormGenerations;

    /**
     * @ORM\OneToMany(targetEntity=PokemonForm::class, mappedBy="versionGroup")
     */
    private $pokemonForms;

    /**
     * @ORM\OneToMany(targetEntity=Version::class, mappedBy="versionGroup")
     */
    private $versions;

    /**
     * @ORM\OneToMany(targetEntity=PokemonMoveAvailability::class, mappedBy="versionGroup")
     */
    private $pokemonMoveAvailabilities;

    public function __construct()
    {
        $this->machines = new ArrayCollection();
        $this->pokemonMoves = new ArrayCollection();
        $this->pokedexVersionGroups = new ArrayCollection();
        $this->pokemonFormGenerations = new ArrayCollection();
        $this->pokemonForms = new ArrayCollection();
        $this->versions = new ArrayCollection();
        $this->pokemonMoveAvailabilities = new ArrayCollection();
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

    public function getVersionGroupOrder(): ?int
    {
        return $this->versionGroupOrder;
    }

    public function setVersionGroupOrder(int $versionGroupOrder): self
    {
        $this->versionGroupOrder = $versionGroupOrder;

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

    /**
     * @return Collection|Machine[]
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(Machine $machine): self
    {
        if (!$this->machines->contains($machine)) {
            $this->machines[] = $machine;
            $machine->setVersionGroup($this);
        }

        return $this;
    }

    public function removeMachine(Machine $machine): self
    {
        if ($this->machines->removeElement($machine)) {
            // set the owning side to null (unless already changed)
            if ($machine->getVersionGroup() === $this) {
                $machine->setVersionGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonMove[]
     */
    public function getPokemonMoves(): Collection
    {
        return $this->pokemonMoves;
    }

    public function addPokemonMove(PokemonMove $pokemonMove): self
    {
        if (!$this->pokemonMoves->contains($pokemonMove)) {
            $this->pokemonMoves[] = $pokemonMove;
            $pokemonMove->setVersionGroup($this);
        }

        return $this;
    }

    public function removePokemonMove(PokemonMove $pokemonMove): self
    {
        if ($this->pokemonMoves->removeElement($pokemonMove)) {
            // set the owning side to null (unless already changed)
            if ($pokemonMove->getVersionGroup() === $this) {
                $pokemonMove->setVersionGroup(null);
            }
        }

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
            $pokedexVersionGroup->setVersionGroup($this);
        }

        return $this;
    }

    public function removePokedexVersionGroup(PokedexVersionGroup $pokedexVersionGroup): self
    {
        if ($this->pokedexVersionGroups->removeElement($pokedexVersionGroup)) {
            // set the owning side to null (unless already changed)
            if ($pokedexVersionGroup->getVersionGroup() === $this) {
                $pokedexVersionGroup->setVersionGroup(null);
            }
        }

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
            $pokemonFormGeneration->setVersionGroup($this);
        }

        return $this;
    }

    public function removePokemonFormGeneration(PokemonFormGeneration $pokemonFormGeneration): self
    {
        if ($this->pokemonFormGenerations->removeElement($pokemonFormGeneration)) {
            // set the owning side to null (unless already changed)
            if ($pokemonFormGeneration->getVersionGroup() === $this) {
                $pokemonFormGeneration->setVersionGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonForm[]
     */
    public function getPokemonForms(): Collection
    {
        return $this->pokemonForms;
    }

    public function addPokemonForm(PokemonForm $pokemonForm): self
    {
        if (!$this->pokemonForms->contains($pokemonForm)) {
            $this->pokemonForms[] = $pokemonForm;
            $pokemonForm->setVersionGroup($this);
        }

        return $this;
    }

    public function removePokemonForm(PokemonForm $pokemonForm): self
    {
        if ($this->pokemonForms->removeElement($pokemonForm)) {
            // set the owning side to null (unless already changed)
            if ($pokemonForm->getVersionGroup() === $this) {
                $pokemonForm->setVersionGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Version[]
     */
    public function getVersions(): Collection
    {
        return $this->versions;
    }

    public function addVersion(Version $version): self
    {
        if (!$this->versions->contains($version)) {
            $this->versions[] = $version;
            $version->setVersionGroup($this);
        }

        return $this;
    }

    public function removeVersion(Version $version): self
    {
        if ($this->versions->removeElement($version)) {
            // set the owning side to null (unless already changed)
            if ($version->getVersionGroup() === $this) {
                $version->setVersionGroup(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PokemonMoveAvailability[]
     */
    public function getPokemonMoveAvailabilities(): Collection
    {
        return $this->pokemonMoveAvailabilities;
    }

    public function addPokemonMoveAvailability(PokemonMoveAvailability $pokemonMoveAvailability): self
    {
        if (!$this->pokemonMoveAvailabilities->contains($pokemonMoveAvailability)) {
            $this->pokemonMoveAvailabilities[] = $pokemonMoveAvailability;
            $pokemonMoveAvailability->setVersionGroup($this);
        }

        return $this;
    }

    public function removePokemonMoveAvailability(PokemonMoveAvailability $pokemonMoveAvailability): self
    {
        if ($this->pokemonMoveAvailabilities->removeElement($pokemonMoveAvailability)) {
            // set the owning side to null (unless already changed)
            if ($pokemonMoveAvailability->getVersionGroup() === $this) {
                $pokemonMoveAvailability->setVersionGroup(null);
            }
        }

        return $this;
    }
}
