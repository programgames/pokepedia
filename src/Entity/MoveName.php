<?php

namespace App\Entity;

use App\Repository\MoveNameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MoveNameRepository::class)
 */
class MoveName
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer", name="move_identifier")
     */
    private ?int $moveIdentifier;

    /**
     * @ORM\Column(type="integer", name="language_id")
     */
    private ?int $languageId;

    /**
     * @ORM\Column(type="string", length=255, name="name")
     */
    private ?string $name;

    /**
     * @ORM\OneToMany(targetEntity=MoveAlias::class, mappedBy="moveName", orphanRemoval=true)
     */
    private Collection $moveAliases;

    public function __construct()
    {
        $this->moveAliases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMoveIdentifier(): ?int
    {
        return $this->moveIdentifier;
    }

    public function setMoveIdentifier(int $moveIdentifier): self
    {
        $this->moveIdentifier = $moveIdentifier;

        return $this;
    }

    public function getLanguageId(): ?int
    {
        return $this->languageId;
    }

    public function setLanguageId(int $languageId): self
    {
        $this->languageId = $languageId;

        return $this;
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
     * @return Collection|MoveAlias[]
     */
    public function getMoveAliases(): Collection
    {
        return $this->moveAliases;
    }

    public function addMoveAlias(MoveAlias $moveAlias): self
    {
        if (!$this->moveAliases->contains($moveAlias)) {
            $this->moveAliases[] = $moveAlias;
            $moveAlias->setMoveName($this);
        }

        return $this;
    }

    public function removeMoveAlias(MoveAlias $moveAlias): self
    {
        if ($this->moveAliases->removeElement($moveAlias)) {
            // set the owning side to null (unless already changed)
            if ($moveAlias->getMoveName() === $this) {
                $moveAlias->setMoveName(null);
            }
        }

        return $this;
    }
}
