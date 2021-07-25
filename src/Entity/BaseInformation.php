<?php

namespace App\Entity;

use App\Repository\BaseInformationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BaseInformationRepository::class)
 */
class BaseInformation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable="true")
     */
    private $family;

    /**
     * @ORM\OneToOne(targetEntity=Pokemon::class, mappedBy="baseInformation", cascade={"persist", "remove"})
     */
    private $pokemon;

    /**
     * @ORM\Column(type="string", length=255, nullable="true")
     */
    private $type1;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        // unset the owning side of the relation if necessary
        if ($pokemon === null && $this->pokemon !== null) {
            $this->pokemon->setBaseInformation(null);
        }

        // set the owning side of the relation if necessary
        if ($pokemon !== null && $pokemon->getBaseInformation() !== $this) {
            $pokemon->setBaseInformation($this);
        }

        $this->pokemon = $pokemon;

        return $this;
    }

    public function getType1(): ?string
    {
        return $this->type1;
    }

    public function setType1(string $type1): self
    {
        $this->type1 = $type1;

        return $this;
    }
}
