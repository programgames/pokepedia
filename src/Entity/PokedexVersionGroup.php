<?php

namespace App\Entity;

use App\Repository\PokedexVersionGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokedexVersionGroupRepository::class)
 */
class PokedexVersionGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Pokedex::class, inversedBy="pokedexVersionGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $pokedex;

    /**
     * @ORM\ManyToOne(targetEntity=VersionGroup::class, inversedBy="pokedexVersionGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $versionGroup;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokedex(): ?Pokedex
    {
        return $this->pokedex;
    }

    public function setPokedex(?Pokedex $pokedex): self
    {
        $this->pokedex = $pokedex;

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
