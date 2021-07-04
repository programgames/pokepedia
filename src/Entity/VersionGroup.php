<?php

namespace App\Entity;

use App\Repository\VersionGroupRepository;
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
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $versionGroupOrder;

    /**
     * @ORM\ManyToOne(targetEntity=Generation::class, inversedBy="versionGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $generation;

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

    public function setGeneration(?Generation $generation): self
    {
        $this->generation = $generation;

        return $this;
    }
}
