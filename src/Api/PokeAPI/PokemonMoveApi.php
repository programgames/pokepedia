<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use App\Entity\Machine;
use App\Entity\Move;
use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class PokemonMoveApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getMovesByPokemon(): \Generator
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
      pokemon_v2_pokemon {
        name
      }
    }
  }
}

GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'pokemonmove'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $i =0;
        foreach ($json['data']['pokemon_v2_pokemon'] as $pokemon) {
            foreach ($pokemon['pokemon_v2_pokemonmoves'] as $pokemonMove) {
                $pokemonMoveEntity = new PokemonMove();
                $pokemonMoveEntity->setLevel($pokemonMove['level']);
                /** @var MoveLearnMethod $learnMethod */
                $learnMethod = $this->entityManager->getRepository(MoveLearnMethod::class)->findOneBy(
                    [
                        'name' => $pokemonMove['pokemon_v2_movelearnmethod']['name']
                    ]
                );
                /** @var VersionGroup $versionGroup */
                $versionGroup = $this->entityManager->getRepository(VersionGroup::class)->findOneBy(
                    [
                        'name' =>$pokemonMove['pokemon_v2_versiongroup']['name']
                    ]
                );
                /** @var Move $move */
                $move = $this->entityManager->getRepository(Move::class)->findOneBy(
                    [
                        'name' => $pokemonMove['pokemon_v2_move']['name']
                    ]
                );
                /** @var Pokemon $pokemonEntity */
                $pokemonEntity = $this->entityManager->getRepository(Pokemon::class)->findOneBy(
                    [
                        'name' => $pokemonMove['pokemon_v2_pokemon']['name']
                    ]
                );
                $pokemonMoveEntity->setVersionGroup($versionGroup);
                $pokemonMoveEntity->setLearnMethod($learnMethod);
                $pokemonMoveEntity->setMove($move);

                $pokemonMoveEntity->setPokemon($pokemonEntity);
                yield $pokemonMoveEntity;
            }
        }
    }
}
