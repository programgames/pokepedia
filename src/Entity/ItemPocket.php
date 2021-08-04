<?php

namespace App\Entity;

use App\Repository\ItemPocketRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemPocketRepository::class)
 */
class ItemPocket
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ItemCategory::class, mappedBy="itemPocket")
     */
    private $itemCategories;

    public function __construct()
    {
        $this->itemCategories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ItemCategory[]
     */
    public function getItemCategories(): Collection
    {
        return $this->itemCategories;
    }

    public function addItemCategory(ItemCategory $itemCategory): self
    {
        if (!$this->itemCategories->contains($itemCategory)) {
            $this->itemCategories[] = $itemCategory;
            $itemCategory->setItemPocket($this);
        }

        return $this;
    }

    public function removeItemCategory(ItemCategory $itemCategory): self
    {
        if ($this->itemCategories->removeElement($itemCategory)) {
            // set the owning side to null (unless already changed)
            if ($itemCategory->getItemPocket() === $this) {
                $itemCategory->setItemPocket(null);
            }
        }

        return $this;
    }
}
