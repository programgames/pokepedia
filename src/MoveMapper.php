<?php

namespace App;

class MoveMapper
{
    function mapMoves(\App\Entity\Pokemon $pokemon, array $move, \App\Entity\Generation $generation, \Doctrine\Persistence\ObjectManager $em, \App\Entity\MoveLearnMethod $learnMethod)
    {
        if ($move['type'] === 'level' && $generation->getGenerationIdentifier() === 7 && $move['format'] === 'global') {
            $moveName = $em->getRepository(\App\Entity\MoveName::class)->findEnglishMoveNameByName($move['value'][2], 7);
            $versionGroupEntity = $em->getRepository(\App\Entity\VersionGroup::class)->findOneBy(array('name' => 'lets-go'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new \App\Entity\PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $pokemonMoveEntity0->setLevel(\App\Helper\MoveSetHelper::convertLevel($move['value'][1]));
            $em->persist($pokemonMoveEntity0);
        }
        if ($move['type'] === 'level' && $generation->getGenerationIdentifier() === 8 && $move['format'] === 'global') {
            $moveName = $em->getRepository(\App\Entity\MoveName::class)->findEnglishMoveNameByName($move['value'][2], 8);
            $versionGroupEntity = $em->getRepository(\App\Entity\VersionGroup::class)->findOneBy(array('name' => 'sword-shield'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new \App\Entity\PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $pokemonMoveEntity0->setLevel(\App\Helper\MoveSetHelper::convertLevel($move['value'][1]));
            $em->persist($pokemonMoveEntity0);
        }
        if ($move['type'] === 'tutor' && $generation->getGenerationIdentifier() === 7 && $move['format'] === 'global') {
            $moveName = $em->getRepository(\App\Entity\MoveName::class)->findEnglishMoveNameByName($move['value'][1], 7);
            $versionGroupEntity = $em->getRepository(\App\Entity\VersionGroup::class)->findOneBy(array('name' => 'lets-go'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new \App\Entity\PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $em->persist($pokemonMoveEntity0);
        }
        if ($move['type'] === 'tm' && $generation->getGenerationIdentifier() === 7 && $move['format'] === 'global') {
            $moveName = $em->getRepository(\App\Entity\MoveName::class)->findEnglishMoveNameByName($move['value'][2], 7);
            $versionGroupEntity = $em->getRepository(\App\Entity\VersionGroup::class)->findOneBy(array('name' => 'lets-go'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new \App\Entity\PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $em->persist($pokemonMoveEntity0);
        }
        else {
            throw new \App\Exception\UnknownMapping(sprintf('Unknown mapping format : %s / gen : %s ', $move['format'], $generation->getGenerationIdentifier()));
        }
    }
}