<?php


namespace App\Manager;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Game;
use App\Entity\LevelingUpMove;
use App\Entity\Pokemon;
use App\Exception\UnknownMapping;
use App\Generation\GenerationHelper;
use App\Helper\MoveSetHelper;
use App\Helper\NumberHelper;
use Doctrine\ORM\EntityManagerInterface;

class MoveSetManager
{
    private EntityManagerInterface $entityManager;
    private BulbapediaMovesAPI $bulbapediaMovesAPI;
    private array $games = [];

    public function __construct(EntityManagerInterface $entityManager, BulbapediaMovesAPI $bulbapediaMovesAPI)
    {
        $this->entityManager = $entityManager;
        $this->bulbapediaMovesAPI = $bulbapediaMovesAPI;
    }

    public function importTutoringMoves(Pokemon $pokemon, int $gen)
    {
        if (empty($this->games)) {
            $this->games = $this->entityManager->getRepository(Game::class)->findAllAssociative();
        }

        $moveNames = $this->bulbapediaMovesAPI->getTutorMoves($pokemon, GenerationHelper::genNumberToLitteral($gen));
        foreach ($moveNames as $moveInformation) {
            if ($moveInformation['format'] === 'numeral') {

            }
        }
        $this->entityManager->flush();
    }

    public function importLevelingMoves(Pokemon $pokemon, int $gen)
    {
        if (empty($this->games)) {
            $this->games = $this->entityManager->getRepository(Game::class)->findAllAssociative();
        }

        $moveInformations = $this->bulbapediaMovesAPI->getLevelMoves($pokemon, $gen);
        foreach ($moveInformations as $form => $movesByForm) {
            foreach ($movesByForm as $move) {
                if ($move['format'] === 'numeral' && $gen === 5) {

                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][2]);
                    $moveEntity1->setAttackType($move['value'][3]);
                    $moveEntity1->setCategory($move['value'][4]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_GLOBAL);
                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity1);
                } else if ($move['format'] === 'roman' && $gen === 5) {


                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][3]);
                    $moveEntity1->setAttackType($move['value'][4]);
                    $moveEntity1->setCategory($move['value'][5]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][8]));
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $moveEntity1->setBlack(true);
                    $moveEntity1->setWhite(true);
                    $this->entityManager->persist($moveEntity1);

                    $moveEntity2 = new LevelingUpMove();
                    $moveEntity2->setPokemon($pokemon);
                    $moveEntity2->setGeneration($gen);
                    $moveEntity2->setLevel(NumberHelper::formatNumber($move['value'][2]));
                    $moveEntity2->setMove($move['value'][3]);
                    $moveEntity2->setAttackType($move['value'][4]);
                    $moveEntity2->setCategory($move['value'][5]);
                    $moveEntity2->setPower(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity2->setAccuracy(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity2->setPowerPoints(NumberHelper::formatNumber($move['value'][8]));
                    $moveEntity2->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    $moveEntity2->setForm($form === "noform" ? null : $form);
                    $moveEntity2->setBlack2(true);
                    $moveEntity2->setWhite2(true);
                    $this->entityManager->persist($moveEntity2);
                } else if ($move['format'] === 'numeral' && ($gen === 1 || $gen  === 2)) {
                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][2]);
                    $moveEntity1->setAttackType($move['value'][3]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][4]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_GLOBAL);
                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity1);

                } else if ($move['format'] === 'roman' && ($gen === 1 || $gen === 2)) {

                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][3]);
                    $moveEntity1->setAttackType($move['value'][4]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    if($gen ===  1) {
                        $moveEntity1->setRed(true);
                        $moveEntity1->setBlue(true);
                        $moveEntity1->setGreen(true);
                    }  elseif ($gen === 2) {
                        $moveEntity1->setGold(true);
                        $moveEntity1->setSilver(true);
                    }

                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity1);

                    $moveEntity2 = new LevelingUpMove();
                    $moveEntity2->setPokemon($pokemon);
                    $moveEntity2->setGeneration($gen);
                    $moveEntity2->setLevel(NumberHelper::formatNumber($move['value'][2]));
                    $moveEntity2->setMove($move['value'][3]);
                    $moveEntity2->setAttackType($move['value'][4]);
                    $moveEntity2->setPower(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity2->setAccuracy(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity2->setPowerPoints(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity2->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    if($gen === 1) {
                        $moveEntity2->setYellow(true);
                    } elseif ($gen === 2) {
                        $moveEntity2->setCrystal(true);
                    }
                    $moveEntity2->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity2);

                } elseif ($move['format'] === 'numeral' && $gen === 3) {

                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][2]);
                    $moveEntity1->setAttackType($move['value'][3]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][4]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setConstest($move['value'][7]);
                    $moveEntity1->setAppeal($move['value'][8]);
                    $moveEntity1->setJam(NumberHelper::formatNumber($move['value'][9]));
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_GLOBAL);
                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity1);
                } else if ($move['format'] === 'roman' && ($gen === 3)) {

                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][3]);
                    $moveEntity1->setAttackType($move['value'][4]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity1->setConstest($move['value'][8]);
                    $moveEntity1->setAppeal($move['value'][9]);
                    $moveEntity1->setJam(NumberHelper::formatNumber($move['value'][10]));
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    if($gen ===  3) {
                        $moveEntity1->setRuby(true);
                        $moveEntity1->setSapphire(true);
                        $moveEntity1->setEmerald(true);
                    }

                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity1);

                    $moveEntity2 = new LevelingUpMove();
                    $moveEntity2->setPokemon($pokemon);
                    $moveEntity2->setGeneration($gen);
                    $moveEntity2->setLevel(NumberHelper::formatNumber($move['value'][2]));
                    $moveEntity2->setMove($move['value'][3]);
                    $moveEntity2->setAttackType($move['value'][4]);
                    $moveEntity2->setPower(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity2->setPowerPoints(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity2->setConstest($move['value'][8]);
                    $moveEntity2->setAppeal($move['value'][9]);
                    $moveEntity2->setJam(NumberHelper::formatNumber($move['value'][10]));
                    $moveEntity2->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    if($gen === 3) {
                        $moveEntity2->setFireRed(true);
                        $moveEntity2->setLeafGreen(true);
                    }
                    $moveEntity2->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity2);

                } elseif ($move['format'] === 'numeral' && $gen === 4) {

                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][2]);
                    $moveEntity1->setAttackType($move['value'][3]);
                    $moveEntity1->setCategory($move['value'][4]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][5]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity1->setConstest($move['value'][8]);
                    $moveEntity1->setAppeal($move['value'][9]);
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_GLOBAL);
                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity1);
                } else if ($move['format'] === 'roman' && ($gen === 4)) {

                    $moveEntity1 = new LevelingUpMove();
                    $moveEntity1->setPokemon($pokemon);
                    $moveEntity1->setGeneration($gen);
                    $moveEntity1->setLevel(NumberHelper::formatNumber($move['value'][1]));
                    $moveEntity1->setMove($move['value'][3]);
                    $moveEntity1->setAttackType($move['value'][4]);
                    $moveEntity1->setCategory($move['value'][5]);
                    $moveEntity1->setPower(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity1->setAccuracy(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity1->setPowerPoints(NumberHelper::formatNumber($move['value'][8]));
                    $moveEntity1->setConstest($move['value'][9]);
                    $moveEntity1->setAppeal($move['value'][10]);
                    $moveEntity1->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    if($gen ===  3) {
                        $moveEntity1->setRuby(true);
                        $moveEntity1->setSapphire(true);
                        $moveEntity1->setEmerald(true);
                    }

                    $moveEntity1->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity1);

                    $moveEntity2 = new LevelingUpMove();
                    $moveEntity2->setPokemon($pokemon);
                    $moveEntity2->setGeneration($gen);
                    $moveEntity2->setLevel(NumberHelper::formatNumber($move['value'][2]));
                    $moveEntity2->setMove($move['value'][3]);
                    $moveEntity2->setAttackType($move['value'][4]);
                    $moveEntity2->setCategory($move['value'][5]);
                    $moveEntity2->setPower(NumberHelper::formatNumber($move['value'][6]));
                    $moveEntity2->setAccuracy(NumberHelper::formatNumber($move['value'][7]));
                    $moveEntity2->setPowerPoints(NumberHelper::formatNumber($move['value'][8]));
                    $moveEntity2->setConstest($move['value'][9]);
                    $moveEntity2->setAppeal($move['value'][10]);
                    $moveEntity2->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                    if($gen === 3) {
                        $moveEntity2->setFireRed(true);
                        $moveEntity2->setLeafGreen(true);
                    }
                    $moveEntity2->setForm($form === "noform" ? null : $form);
                    $this->entityManager->persist($moveEntity2);

                } else {
                    throw new UnknownMapping(sprintf('Unknown mapping , format : %s / gen : %s', $move['format'], $gen));
                }

            }
        }
        $this->entityManager->flush();
    }
}
