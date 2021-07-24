<?php

namespace App\Entity;

use App\Repository\MoveNameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoveNameRepository::class)
 */
class MoveName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $language;

    /**
     * @ORM\ManyToOne(targetEntity=Move::class, inversedBy="moveNames")
     */
    private ?Move $move;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen7;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $gen8;

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

    public function getLanguage(): ?int
    {
        return $this->language;
    }

    public function setLanguage(int $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getMove(): ?Move
    {
        return $this->move;
    }

    public function setMove(?Move $move): self
    {
        $this->move = $move;

        return $this;
    }

    public function getGen1(): ?string
    {
        return $this->gen1;
    }

    public function setGen1(?string $gen1): self
    {
        $this->gen1 = $gen1;

        return $this;
    }

    public function getGen2(): ?string
    {
        return $this->gen2;
    }

    public function setGen2(?string $gen2): self
    {
        $this->gen2 = $gen2;

        return $this;
    }

    public function getGen3(): ?string
    {
        return $this->gen3;
    }

    public function setGen3(?string $gen3): self
    {
        $this->gen3 = $gen3;

        return $this;
    }

    public function getGen4(): ?string
    {
        return $this->gen4;
    }

    public function setGen4(?string $gen4): self
    {
        $this->gen4 = $gen4;

        return $this;
    }

    public function getGen5(): ?string
    {
        return $this->gen5;
    }

    public function setGen5(?string $gen5): self
    {
        $this->gen5 = $gen5;

        return $this;
    }

    public function getGen6(): ?string
    {
        return $this->gen6;
    }

    public function setGen6(?string $gen6): self
    {
        $this->gen6 = $gen6;

        return $this;
    }

    public function getGen7(): ?string
    {
        return $this->gen7;
    }

    public function setGen7(?string $gen7): self
    {
        $this->gen7 = $gen7;

        return $this;
    }

    public function getGen8(): ?string
    {
        return $this->gen8;
    }

    public function setGen8(?string $gen8): self
    {
        $this->gen8 = $gen8;

        return $this;
    }
}
