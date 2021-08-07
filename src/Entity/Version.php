<?php

namespace App\Entity;

use App\Repository\VersionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VersionRepository::class)
 */
class Version
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
     * @ORM\OneToMany(targetEntity=PokemonGameIndex::class, mappedBy="version")
     */
    private $pokemonGameIndices;

    /**
     * @ORM\ManyToOne(targetEntity=VersionGroup::class, inversedBy="versions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $versionGroup;

    public function __construct()
    {
        $this->pokemonGameIndices = new ArrayCollection();
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
     * @return Collection|PokemonGameIndex[]
     */
    public function getPokemonGameIndices(): Collection
    {
        return $this->pokemonGameIndices;
    }

    public function addPokemonGameIndex(PokemonGameIndex $pokemonGameIndex): self
    {
        if (!$this->pokemonGameIndices->contains($pokemonGameIndex)) {
            $this->pokemonGameIndices[] = $pokemonGameIndex;
            $pokemonGameIndex->setVersion($this);
        }

        return $this;
    }

    public function removePokemonGameIndex(PokemonGameIndex $pokemonGameIndex): self
    {
        if ($this->pokemonGameIndices->removeElement($pokemonGameIndex)) {
            // set the owning side to null (unless already changed)
            if ($pokemonGameIndex->getVersion() === $this) {
                $pokemonGameIndex->setVersion(null);
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
}
