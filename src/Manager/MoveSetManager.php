<?php


namespace App\Manager;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Game;
use App\Entity\GameMoveExtra;
use App\Entity\Move;
use App\Entity\Pokemon;
use App\Generation\GenerationHelper;
use App\MoveSet\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;

class MoveSetManager
{
    private EntityManagerInterface $entityManager;
    private BulbapediaMovesAPI $bulbapediaMovesAPI;

    public function __construct(EntityManagerInterface $entityManager, BulbapediaMovesAPI $bulbapediaMovesAPI)
    {
        $this->entityManager = $entityManager;
        $this->bulbapediaMovesAPI = $bulbapediaMovesAPI;
    }

    public function importTutoringMoves(Pokemon $pokemon, int $gen)
    {
        $moveNames = $this->bulbapediaMovesAPI->getTutorMoves($pokemon, GenerationHelper::genNumberToLitteral($gen));
        foreach ($moveNames as $moveName) {
            $move = new Move();
            $move->addPokemon($pokemon);
            $move->setEnglishName($moveName[1]);
            $games = [];
            if (array_key_exists(9, $moveName) && $moveName[9] === 'yes') {
                $games['B/W'] = true;
            }
            if (array_key_exists(10, $moveName) && $moveName[10] === 'yes') {
                $games['B2/W2'] = true;
            }
            $move->setGames(json_encode($games));
            $move->setLearningType(MoveSetHelper::TUTORING_TYPE);
            $move->setGeneration($gen);
            $this->entityManager->persist($move);
        }
        $this->entityManager->flush();
    }

    public function importLevelingMoves(Pokemon $pokemon, int $gen)
    {
        $moveInformations = $this->bulbapediaMovesAPI->getLevelMoves($pokemon, GenerationHelper::genNumberToLitteral($gen));
        foreach ($moveInformations as $moveInformation) {
            $move = new Move();
            $move->addPokemon($pokemon);

            if($moveInformation['format'] === 'numeral') {
                $move->setEnglishName($moveInformation[1]);
                $games = [];
                if (array_key_exists(9, $moveInformation['value']) && $moveInformation['value'][9] === 'yes') {
                    $games['B/W'] = true;
                }
                if (array_key_exists(10, $moveInformation['value']) && $moveInformation['value'][10] === 'yes') {
                    $games['B2/W2'] = true;
                }
                $move->setGames(json_encode($games));
            } else {
                $move->setEnglishName($moveInformation['value'][3]);
                $gameExtra1 = new GameMoveExtra();
                $game = $this->entityManager->getRepository(Game::class)
                    ->findByGenAndOrder($gen,true);

                $gameExtra1->setGame($game);
                $gameExtra1->setStartAt((int)$moveInformation['value'][1]);

                $gameExtra2 = new GameMoveExtra();
                $game = $this->entityManager->getRepository(Game::class)
                    ->findByGenAndOrder($gen,true);

                $gameExtra2->setGame($game);
                $gameExtra2->setStartAt($moveInformation['value'][2]);
                $this->entityManager->persist($gameExtra1);
                $this->entityManager->persist($gameExtra2);

                $move->addGameExtra($gameExtra1);
                $move->addGameExtra($gameExtra2);
            }
            $move->setLearningType(MoveSetHelper::TUTORING_TYPE);
            $move->setGeneration($gen);
            $this->entityManager->persist($move);
        }
        $this->entityManager->flush();
    }
}