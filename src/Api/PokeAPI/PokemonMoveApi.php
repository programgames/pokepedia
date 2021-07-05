<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use App\Entity\Machine;
use App\Entity\Move;
use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
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

    public function getMovesByPokemon($id1): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemon(where: {id: {_gte: 1, _lte: 1}}) {
    pokemon_v2_pokemonmoves {
      pokemon_v2_move {
        pokemon_v2_movenames(where: {pokemon_v2_language: {name: {_eq: "fr"}}}) {
          name
        }
        pokemon_v2_machines {
          pokemon_v2_item {
            pokemon_v2_itemnames(where: {pokemon_v2_language: {name: {_eq: "fr"}}}, distinct_on: name) {
              name
            }
          }
        }
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
        $pokemonMoves = [];
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
                $versionGroup = $this->entityManager->getRepository(Item::class)->findOneBy(
                    [
                        'name' =>$pokemonMove['pokemon_v2_versiongroup']['name']
                    ]
                );
                $move = $this->entityManager->getRepository(Move::class)->findOneBy(
                    [
                        'name' => $machine['pokemon_v2_versiongroup']['name']
                    ]
                );
                $pokemonMoveEntity->getVersionGroup($versionGroup);
                $pokemonMoveEntity->setLearnMethod($learnMethod);
                $pokemonMoveEntity->setPokemon($pokemon);
                $pokemonMoves[] = $pokemonMoveEntity;
            }
        }
        return $pokemonMoves;
    }
}