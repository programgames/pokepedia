<?php


namespace App\Manager;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
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
}