<?php

namespace App;

class MoveMapper
{
    function mapMoves(\App\Entity\Pokemon $pokemon, array $move, \App\Entity\Generation $generation, string $format, \Doctrine\Persistence\ObjectManager $em, \App\Entity\MoveLearnMethod $learnMethod)
    {
        if ($move['type'] === 'leveling_up' && $generation->getGenerationIdentifier() === 7.0 && $format === 'global') {
            $pokemonMoveEntity = new \App\Entity\PokemonMove();
            $pokemonMoveEntity->setPokemon($pokemon);
            $pokemonMoveEntity->setLearnMethod($learnMethod);
            if($learnMethod->getName() === 'level-up') {
                $pokemonMoveEntity->setLevel(\App\Helper\MoveSetHelper::convertLevel($move['value'][1]));
            }
            $em->persist($moveEntity);
        }
        else {
            throw new \App\Exception\UnknownMapping(sprintf('Unknown mapping format : %s / gen : %s ', $format, $generation));
        }
    }
}