<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Move;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;
use Generator;

//extract and transform pokemon moves information into entities from pokeapi
class PokemonMoveApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;
    private array $learnMethodCache = [];
    private array $versionGroupCache = [];
    private array $moveCache = [];

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getMovesByPokemon(): Generator
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemon {
    pokemon_v2_pokemonmoves {
      pokemon_v2_move {
        name
      }
      level
      pokemon_v2_movelearnmethod {
        name
      }
      pokemon_v2_versiongroup {
        name
      }
    }
    name
  }
}

GRAPHQL;

        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        foreach ($content['data']['pokemon_v2_pokemon'] as $pokemon) {
            /** @var Pokemon $pokemonEntity */
            $pokemonEntity = $this->entityManager->getRepository(Pokemon::class)->findOneBy(
                [
                    'name' => $pokemon['name']
                ]
            );
            foreach ($pokemon['pokemon_v2_pokemonmoves'] as $pokemonMove) {
                $pokemonMoveEntity = new PokemonMove();
                $pokemonMoveEntity->setLevel($pokemonMove['level']);
                $learnMethod = $this->getLearnMethod($pokemonMove['pokemon_v2_movelearnmethod']['name']);
                $versionGroup = $this->getVersionGroup($pokemonMove['pokemon_v2_versiongroup']['name']);
                $move = $this->getMove($pokemonMove['pokemon_v2_move']['name']);

                $pokemonMoveEntity->setVersionGroup($versionGroup);
                $pokemonMoveEntity->setLearnMethod($learnMethod);
                $pokemonMoveEntity->setMove($move);
                $pokemonMoveEntity->setPokemon($pokemonEntity);
                yield $pokemonMoveEntity;
            }
        }
    }

    private function getLearnMethod($name)
    {
        if (!array_key_exists($name, $this->learnMethodCache)) {
            $this->learnMethodCache[$name] = $this->entityManager->getRepository(MoveLearnMethod::class)->findOneBy(
                [
                    'name' => $name
                ]
            );
        }
        return $this->learnMethodCache[$name];
    }

    private function getVersionGroup($name)
    {
        if (!array_key_exists($name, $this->versionGroupCache)) {
            $this->versionGroupCache[$name] = $this->entityManager->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => $name
                ]
            );
        }
        return $this->versionGroupCache[$name];
    }

    private function getMove($name)
    {
        if (!array_key_exists($name, $this->moveCache)) {
            $this->moveCache[$name] = $this->entityManager->getRepository(Move::class)->findOneBy(
                [
                    'name' => $name
                ]
            );
        }
        return $this->moveCache[$name];
    }
}
