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
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="move_identifier")
     */
    private $moveIdentifier;

    /**
     * @ORM\Column(type="integer", name="language_id")
     */
    private $languageId;

    /**
     * @ORM\Column(type="string", length=255, name="name")
     */
    private $name;

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
}
