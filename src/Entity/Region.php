<?php

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegionRepository::class)
 */
class Region
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
     * @ORM\OneToMany(targetEntity=Pokedex::class, mappedBy="region")
     */
    private $pokedexes;

    public function __construct()
    {
        $this->pokedexes = new ArrayCollection();
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
     * @return Collection|Pokedex[]
     */
    public function getPokedexes(): Collection
    {
        return $this->pokedexes;
    }

    public function addPokedex(Pokedex $pokedex): self
    {
        if (!$this->pokedexes->contains($pokedex)) {
            $this->pokedexes[] = $pokedex;
            $pokedex->setRegion($this);
        }

        return $this;
    }

    public function removePokedex(Pokedex $pokedex): self
    {
        if ($this->pokedexes->removeElement($pokedex)) {
            // set the owning side to null (unless already changed)
            if ($pokedex->getRegion() === $this) {
                $pokedex->setRegion(null);
            }
        }

        return $this;
    }
}
