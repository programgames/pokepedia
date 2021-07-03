<?php

namespace App\Manager;

use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Game;
use App\Entity\Pokemon;
use App\Generation\GenerationHelper;
use App\Helper\MoveSetHelper;
use App\MoveMapper;
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
            foreach ($movesByForm as $moves) {
                (new MoveMapper())->mapMoves($pokemon,$moves,$form,$gen,MoveSetHelper::LEVELING_UP_TYPE);
            }
        }
        $this->entityManager->flush();
    }
}
