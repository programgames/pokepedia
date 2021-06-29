<?php


namespace App\Manager;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Game;
use App\Entity\LevelingUpMove;
use App\Entity\Pokemon;
use App\Entity\TutoringMove;
use App\Generation\GenerationHelper;
use App\MoveSet\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\RuntimeException;

class MoveSetMapper
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
        if(empty($this->games)) {
            $this->games = $this->entityManager->getRepository(Game::class)->findAllAssociative();
        }

        $moveNames = $this->bulbapediaMovesAPI->getTutorMoves($pokemon, GenerationHelper::genNumberToLitteral($gen));
        foreach ($moveNames as $moveInformation) {
            $move = new TutoringMove();
            $move->setPokemon($pokemon);
            $move->setEnglishName($moveInformation[1]);
            $games = [];
            if (array_key_exists(9, $moveInformation) && $moveInformation[9] === 'yes') {
                $games['B/W'] = true;
            }
            if (array_key_exists(10, $moveInformation) && $moveInformation[10] === 'yes') {
                $games['B2/W2'] = true;
            }
//            $move->setGames(json_encode($games));
            $move->setLearningType(MoveSetHelper::TUTORING_TYPE);
            $move->setGeneration($gen);
            $this->entityManager->persist($move);
        }
        $this->entityManager->flush();
    }

    public function importLevelingMoves(Pokemon $pokemon, int $gen)
    {
        if(empty($this->games)) {
            $this->games = $this->entityManager->getRepository(Game::class)->findAllAssociative();
        }

        $moveInformations = $this->bulbapediaMovesAPI->getLevelMoves($pokemon,$gen);
        foreach ($moveInformations as $moveInformation) {

            if($moveInformation['format'] === 'numeral') {
                $move = new LevelingUpMove();
                $move->setPokemon($pokemon);
                $move->setGeneration($gen);
                $move->setLevel((int)$moveInformation['value'][1]);
                $move->setMove($moveInformation['value'][2]);
                $move->setAttackType($moveInformation['value'][3]);
                $move->setCategory($moveInformation['value'][4]);
                $move->setPower((int)$moveInformation['value'][5]);
                $move->setAccuracy((int) $moveInformation['value'][6]);
                $move->setPowerPoints((int) $moveInformation['value'][7]);
                $move->setType(MoveSetHelper::MOVE_TYPE_GLOBAL);
                $this->entityManager->persist($move);
            } else {
                $move1 = new LevelingUpMove();
                $move1->setPokemon($pokemon);
                $move1->setGeneration($gen);
                $move1->setLevel((int)$moveInformation['value'][1]);
                $move1->setMove($moveInformation['value'][3]);
                $move1->setAttackType($moveInformation['value'][4]);
                $move1->setCategory($moveInformation['value'][5]);
                $move1->setPower((int)$moveInformation['value'][6]);
                $move1->setAccuracy((int) $moveInformation['value'][7]);
                $move1->setPowerPoints((int) $moveInformation['value'][8]);
                $move1->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                $this->addGamesByGenAndOrder($move1,$gen,1);

                $move2 = new LevelingUpMove();
                $move2->setPokemon($pokemon);
                $move2->setGeneration($gen);
                $move2->setLevel((int)$moveInformation['value'][2]);
                $move2->setMove($moveInformation['value'][3]);
                $move2->setAttackType($moveInformation['value'][4]);
                $move2->setCategory($moveInformation['value'][5]);
                $move2->setPower((int)$moveInformation['value'][6]);
                $move2->setAccuracy((int) $moveInformation['value'][7]);
                $move2->setPowerPoints((int) $moveInformation['value'][8]);
                $move2->setType(MoveSetHelper::MOVE_TYPE_SPECIFIC);
                $this->addGamesByGenAndOrder($move1,$gen,2);

                $this->entityManager->persist($move1);
                $this->entityManager->persist($move2);


            }

        }
        $this->entityManager->flush();
    }

    private function addGamesByGenAndOrder(LevelingUpMove $move1, int $gen, int $order)
    {
        if($gen === 5 && $order == 1) {
            $move1->addGame($this->games['black']);
            $move1->addGame($this->games['white']);

        } elseif ($gen === 5 && $order == 2) {
            $move1->addGame($this->games['black2']);
            $move1->addGame($this->games['white2']);
        } else {
            throw new \RuntimeException('Unknown gen/order combo');
        }
    }
}