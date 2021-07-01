<?php

namespace App\Entity;

use App\Repository\LevelingUpMoveRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LevelingUpMoveRepository::class)
 * @ORM\Table(
 *    name="leveling_up_move"
 * )
 */
class LevelingUpMove
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", name="id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="move")
     */
    private $move;

    /**
     * @ORM\Column(type="integer", nullable=true, name="level")
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255, name="type")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, name="attack_type")
     */
    private $attackType;

    /**
     * @ORM\Column(type="string", length=255, name="category", nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="integer", nullable=true, name="power", nullable=true)
     */
    private $power;

    /**
     * @ORM\Column(type="integer", name="accuracy", nullable=true)
     */
    private $accuracy;

    /**
     * @ORM\Column(type="integer", name="power_points")
     */
    private $powerPoints;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="form")
     */
    private $form;

    /**
     * @ORM\ManyToOne(targetEntity=Pokemon::class, inversedBy="levelingUpMoves")
     * @ORM\JoinColumn(nullable=false, name="pokemon_id")
     */
    private $pokemon;

    /**
     * @ORM\Column(type="integer", name="generation")
     */
    private $generation;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $red;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $blue;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $green;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $yellow;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $silver;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $gold;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $crystal;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $fireRed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $leafGreen;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $emerald;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $diamond;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pearl;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $platinum;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $heartGold;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $soulSilver;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $black;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $white;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $black2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $white2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $x;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $y;

    public function getRed()
    {
        return $this->red;
    }

    public function setRed($red)
    {
        $this->red = $red;
        return $this;
    }

    public function getBlue()
    {
        return $this->blue;
    }

    public function setBlue($blue)
    {
        $this->blue = $blue;
        return $this;
    }

    public function getGreen()
    {
        return $this->green;
    }

    public function setGreen($green)
    {
        $this->green = $green;
        return $this;
    }

    public function getYellow()
    {
        return $this->yellow;
    }

    public function setYellow($yellow)
    {
        $this->yellow = $yellow;
        return $this;
    }

    public function getSilver()
    {
        return $this->silver;
    }

    public function setSilver($silver)
    {
        $this->silver = $silver;
        return $this;
    }


    public function getGold()
    {
        return $this->gold;
    }

    public function setGold($gold)
    {
        $this->gold = $gold;
        return $this;
    }

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $omegaRuby;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $omegaSapphire;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sun;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $moon;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ultraMoon;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ultraSun;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sword;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $shield;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $constest;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $appeal;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jam;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $ruby;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sapphire;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMove(): ?string
    {
        return $this->move;
    }

    public function setMove(string $move): self
    {
        $this->move = $move;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAttackType(): ?string
    {
        return $this->attackType;
    }

    public function setAttackType(string $attackType): self
    {
        $this->attackType = $attackType;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getPower(): ?int
    {
        return $this->power;
    }

    public function setPower(?int $power): self
    {
        $this->power = $power;

        return $this;
    }

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy(?int $accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }

    public function getPowerPoints(): ?int
    {
        return $this->powerPoints;
    }

    public function setPowerPoints(int $powerPoints): self
    {
        $this->powerPoints = $powerPoints;

        return $this;
    }

    public function getForm(): ?string
    {
        return $this->form;
    }

    public function setForm(?string $form): self
    {
        $this->form = $form;

        return $this;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): self
    {
        $this->pokemon = $pokemon;

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

    public function getCrystal(): ?bool
    {
        return $this->crystal;
    }

    public function setCrystal(?bool $crystal): self
    {
        $this->crystal = $crystal;

        return $this;
    }

    public function getFireRed(): ?bool
    {
        return $this->fireRed;
    }

    public function setFireRed(?bool $fireRed): self
    {
        $this->fireRed = $fireRed;

        return $this;
    }

    public function getLeafGreen(): ?bool
    {
        return $this->leafGreen;
    }

    public function setLeafGreen(?bool $leafGreen): self
    {
        $this->leafGreen = $leafGreen;

        return $this;
    }

    public function getEmerald(): ?bool
    {
        return $this->emerald;
    }

    public function setEmerald(?bool $emerald): self
    {
        $this->emerald = $emerald;

        return $this;
    }

    public function getDiamond(): ?bool
    {
        return $this->diamond;
    }

    public function setDiamond(?bool $diamond): self
    {
        $this->diamond = $diamond;

        return $this;
    }

    public function getPearl(): ?bool
    {
        return $this->pearl;
    }

    public function setPearl(?bool $pearl): self
    {
        $this->pearl = $pearl;

        return $this;
    }

    public function getPlatinum(): ?bool
    {
        return $this->platinum;
    }

    public function setPlatinum(?bool $platinum): self
    {
        $this->platinum = $platinum;

        return $this;
    }

    public function getHeartGold(): ?bool
    {
        return $this->heartGold;
    }

    public function setHeartGold(?bool $heartGold): self
    {
        $this->heartGold = $heartGold;

        return $this;
    }

    public function getSoulSilver(): ?bool
    {
        return $this->soulSilver;
    }

    public function setSoulSilver(bool $soulSilver): self
    {
        $this->soulSilver = $soulSilver;

        return $this;
    }

    public function getBlack(): ?bool
    {
        return $this->black;
    }

    public function setBlack(?bool $black): self
    {
        $this->black = $black;

        return $this;
    }

    public function getWhite(): ?bool
    {
        return $this->white;
    }

    public function setWhite(?bool $white): self
    {
        $this->white = $white;

        return $this;
    }

    public function getBlack2(): ?bool
    {
        return $this->black2;
    }

    public function setBlack2(?bool $black2): self
    {
        $this->black2 = $black2;

        return $this;
    }

    public function getWhite2(): ?bool
    {
        return $this->white2;
    }

    public function setWhite2(?bool $white2): self
    {
        $this->white2 = $white2;

        return $this;
    }

    public function getX(): ?bool
    {
        return $this->x;
    }

    public function setX(?bool $x): self
    {
        $this->x = $x;

        return $this;
    }

    public function getY(): ?bool
    {
        return $this->y;
    }

    public function setY(?bool $y): self
    {
        $this->y = $y;

        return $this;
    }

    public function getOmegaRuby(): ?bool
    {
        return $this->omegaRuby;
    }

    public function setOmegaRuby(?bool $omegaRuby): self
    {
        $this->omegaRuby = $omegaRuby;

        return $this;
    }

    public function getOmegaSapphire(): ?bool
    {
        return $this->omegaSapphire;
    }

    public function setOmegaSapphire(?bool $omegaSapphire): self
    {
        $this->omegaSapphire = $omegaSapphire;

        return $this;
    }

    public function getSun(): ?bool
    {
        return $this->sun;
    }

    public function setSun(?bool $sun): self
    {
        $this->sun = $sun;

        return $this;
    }

    public function getMoon(): ?bool
    {
        return $this->moon;
    }

    public function setMoon(?bool $moon): self
    {
        $this->moon = $moon;

        return $this;
    }

    public function getUltraMoon(): ?bool
    {
        return $this->ultraMoon;
    }

    public function setUltraMoon(?bool $ultraMoon): self
    {
        $this->ultraMoon = $ultraMoon;

        return $this;
    }

    public function getUltraSun(): ?bool
    {
        return $this->ultraSun;
    }

    public function setUltraSun(?bool $ultraSun): self
    {
        $this->ultraSun = $ultraSun;

        return $this;
    }

    public function getSword(): ?bool
    {
        return $this->sword;
    }

    public function setSword(?bool $sword): self
    {
        $this->sword = $sword;

        return $this;
    }

    public function getShield(): ?bool
    {
        return $this->shield;
    }

    public function setShield(bool $shield): self
    {
        $this->shield = $shield;

        return $this;
    }

    public function getConstest(): ?string
    {
        return $this->constest;
    }

    public function setConstest(?string $constest): self
    {
        $this->constest = $constest;

        return $this;
    }

    public function getAppeal(): ?int
    {
        return $this->appeal;
    }

    public function setAppeal(?int $appeal): self
    {
        $this->appeal = $appeal;

        return $this;
    }

    public function getJam(): ?int
    {
        return $this->jam;
    }

    public function setJam(?int $jam): self
    {
        $this->jam = $jam;

        return $this;
    }

    public function getRuby(): ?bool
    {
        return $this->ruby;
    }

    public function setRuby(?bool $ruby): self
    {
        $this->ruby = $ruby;

        return $this;
    }

    public function getSapphire(): ?bool
    {
        return $this->sapphire;
    }

    public function setSapphire(?bool $sapphire): self
    {
        $this->sapphire = $sapphire;

        return $this;
    }
}
