<?php

namespace App\Entity;

use App\Repository\MoveAliasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoveAliasRepository::class)
 * @ORM\Table(
 *    name="move_alias",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uniq_alias", columns={"name","move_name_id"})
 *    }
 * )
 */
class MoveAlias
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
     * @ORM\Column(type="string", length=255)
     */
    private ?string $gen;

    /**
     * @ORM\ManyToOne(targetEntity=MoveName::class, inversedBy="moveAliases")
     * @ORM\JoinColumn(nullable=false, name="move_name_id")
     */
    private ?MoveName $moveName;

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

    public function getGen(): ?string
    {
        return $this->gen;
    }

    public function setGen(string $gen): self
    {
        $this->gen = $gen;

        return $this;
    }

    public function getMoveName(): ?MoveName
    {
        return $this->moveName;
    }

    public function setMoveName(?MoveName $moveName): self
    {
        $this->moveName = $moveName;

        return $this;
    }
}
