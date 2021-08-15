<?php


namespace App\Job\Handler;


use App\Handler\PokemonMove\LevelUpPokemonMoveHandler;
use App\Helper\MoveSetHelper;
use App\Job\Message\PokemonMoveSynchro;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PokemonMoveSynchroHandler implements MessageHandlerInterface
{
    private LevelUpPokemonMoveHandler $levelUpMoveHandler;

    public function __construct(LevelUpPokemonMoveHandler $levelUpMoveHandler)
    {
        $this->levelUpMoveHandler = $levelUpMoveHandler;
    }

    public function __invoke(PokemonMoveSynchro $message)
    {
       switch ($message->getType()) {
           case MoveSetHelper::LEVELING_UP_TYPE:
               $this->levelUpMoveHandler->process();
               break;
       }
    }
}