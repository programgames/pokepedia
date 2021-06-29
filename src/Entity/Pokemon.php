<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PokemonRepository::class)
 * @ORM\Table(
 *    name="pokemon",
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="uniq_pokemon", columns={"pokemon_identifier"})
 *    }
 * )
 */
class Pokemon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private ?int $id;


    /**
     * @ORM\Column(type="integer", name="pokemon_identifier")
     */
    private ?int $pokemonIdentifier;

    /**
     * @ORM\Column(type="integer", name="generation")
     */
    private ?int $generation;

    /**
     * @ORM\OneToMany(targetEntity=PokemonName::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private $names;

    /**
     * @ORM\OneToMany(targetEntity=TutoringMove::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private $tutoringMoves;

    /**
     * @ORM\OneToMany(targetEntity=LevelingUpMove::class, mappedBy="pokemon", orphanRemoval=true)
     */
    private $levelingUpMoves;

    public function __construct()
    {
        $this->names = new ArrayCollection();
        $this->tutoringMoves = new ArrayCollection();
        $this->levelingUpMoves = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPokemonIdentifier(): ?int
    {
        return $this->pokemonIdentifier;
    }

    public function setPokemonIdentifier(int $pokemonIdentifier): self
    {
        $this->pokemonIdentifier = $pokemonIdentifier;

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): self
    {
        $this->generation = $generation;

        return $this;
    }

    /**
     * @return Collection|PokemonName[]
     */
    public function getNames(): Collection
    {
        return $this->names;
    }

    public function addName(PokemonName $name): self
    {
        if (!$this->names->contains($name)) {
            $this->names[] = $name;
            $name->setPokemon($this);
        }

        return $this;
    }

    public function removeName(PokemonName $name): self
    {
        // set the owning side to null (unless already changed)
        if ($this->names->removeElement($name) && $name->getPokemon() === $this) {
            $name->setPokemon(null);
        }

        return $this;
    }

    /**
     * @return Collection|TutoringMove[]
     */
    public function getTutoringMoves(): Collection
    {
        return $this->tutoringMoves;
    }

    public function addTutoringMove(TutoringMove $tutoringMove): self
    {
        if (!$this->tutoringMoves->contains($tutoringMove)) {
            $this->tutoringMoves[] = $tutoringMove;
            $tutoringMove->setPokemon($this);
        }

        return $this;
    }

    public function removeTutoringMove(TutoringMove $tutoringMove): self
    {
        if ($this->tutoringMoves->removeElement($tutoringMove)) {
            // set the owning side to null (unless already changed)
            if ($tutoringMove->getPokemon() === $this) {
                $tutoringMove->setPokemon(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LevelingUpMove[]
     */
    public function getLevelingUpMoves(): Collection
    {
        return $this->levelingUpMoves;
    }

    public function addLevelingUpMove(LevelingUpMove $levelingUpMove): self
    {
        if (!$this->levelingUpMoves->contains($levelingUpMove)) {
            $this->levelingUpMoves[] = $levelingUpMove;
            $levelingUpMove->setPokemon($this);
        }

        return $this;
    }

    public function removeLevelingUpMove(LevelingUpMove $levelingUpMove): self
    {
        if ($this->levelingUpMoves->removeElement($levelingUpMove)) {
            // set the owning side to null (unless already changed)
            if ($levelingUpMove->getPokemon() === $this) {
                $levelingUpMove->setPokemon(null);
            }
        }

        return $this;
    }
}
