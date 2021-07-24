<?php

namespace App;

use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use App\Exception\UnknownMapping;
use App\Helper\MoveSetHelper;
use Doctrine\Persistence\ObjectManager;

class MoveMapper
{
    public function mapMoves(Pokemon $pokemon, array $move, Generation $generation, ObjectManager $em, MoveLearnMethod $learnMethod): void
    {
        if ($move['type'] === 'level' && $generation->getGenerationIdentifier() === 7 && $move['format'] === 'global') {
            $moveName = $em->getRepository(MoveName::class)->findEnglishMoveNameByName($move['value'][2], 7);
            $versionGroupEntity = $em->getRepository(VersionGroup::class)->findOneBy(array('name' => 'lets-go'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $pokemonMoveEntity0->setLevel(MoveSetHelper::convertLevel($move['value'][1]));
            $em->persist($pokemonMoveEntity0);
        }
        elseif ($move['type'] === 'level' && $generation->getGenerationIdentifier() === 8 && $move['format'] === 'global') {
            $moveName = $em->getRepository(MoveName::class)->findEnglishMoveNameByName($move['value'][2], 8);
            $versionGroupEntity = $em->getRepository(VersionGroup::class)->findOneBy(array('name' => 'sword-shield'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $pokemonMoveEntity0->setLevel(MoveSetHelper::convertLevel($move['value'][1]));
            $em->persist($pokemonMoveEntity0);
        }
        elseif ($move['type'] === 'tutor' && $generation->getGenerationIdentifier() === 7 && $move['format'] === 'global') {
            $moveName = $em->getRepository(MoveName::class)->findEnglishMoveNameByName($move['value'][1], 7);
            $versionGroupEntity = $em->getRepository(VersionGroup::class)->findOneBy(array('name' => 'lets-go'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $em->persist($pokemonMoveEntity0);
        }
        elseif ($move['type'] === 'tm' && $generation->getGenerationIdentifier() === 7 && $move['format'] === 'global') {
            $moveName = $em->getRepository(MoveName::class)->findEnglishMoveNameByName($move['value'][2], 7);
            $versionGroupEntity = $em->getRepository(VersionGroup::class)->findOneBy(array('name' => 'lets-go'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $em->persist($pokemonMoveEntity0);
        }
        elseif ($move['type'] === 'tm' && $generation->getGenerationIdentifier() === 8 && $move['format'] === 'global') {
            $moveName = $em->getRepository(MoveName::class)->findEnglishMoveNameByName($move['value'][2], 8);
            $versionGroupEntity = $em->getRepository(VersionGroup::class)->findOneBy(array('name' => 'sword-shield'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $em->persist($pokemonMoveEntity0);
        }
        elseif ($move['type'] === 'breed' && $generation->getGenerationIdentifier() === 8 && $move['format'] === 'global') {
            $moveName = $em->getRepository(MoveName::class)->findEnglishMoveNameByName($move['value'][2], 8);
            $versionGroupEntity = $em->getRepository(VersionGroup::class)->findOneBy(array('name' => 'sword-shield'));
            $moveEntity = $moveName->getMove();
            $pokemonMoveEntity0 = new PokemonMove();
            $pokemonMoveEntity0->setPokemon($pokemon);
            $pokemonMoveEntity0->setLearnMethod($learnMethod);
            $pokemonMoveEntity0->setMove($moveEntity);
            $pokemonMoveEntity0->setVersionGroup($versionGroupEntity);
            $em->persist($pokemonMoveEntity0);
        }
        else {
            throw new UnknownMapping(sprintf('Unknown mapping format : %s / gen : %s / learnmethod : %s', $move['format'], $generation->getGenerationIdentifier(), $learnMethod->getName()));
        }
    }
}