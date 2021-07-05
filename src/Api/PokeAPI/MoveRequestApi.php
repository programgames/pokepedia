<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class MoveRequestApi
{

    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getMovesByPokemon(int $pokemonId)
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemon(where: {id: {_eq: $pokemonId}}) {
    id
    pokemon_v2_pokemonspecy {
      pokemon_v2_pokemonegggroups {
        pokemon_v2_egggroup {
          pokemon_v2_egggroupnames(where: {pokemon_v2_language: {name: {_eq: "fr"}}}) {
            name
          }
        }
      }
    }
    pokemon_v2_pokemonmoves {
      pokemon_v2_move {
        pokemon_v2_movenames(where: {pokemon_v2_language: {name: {_eq: "fr"}}}) {
          name
        }
        pokemon_v2_machines(distinct_on: move_id) {
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
    }
  }
}
GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('4pokeapi.%s,%s', 'moves', $pokemonId),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );

        $pokemonData = $json['data']['pokemon_v2_pokemon'][0];

        $rawMoves = $pokemonData['pokemon_v2_pokemonmoves'];
        $formattedMoves = [];
        foreach ($rawMoves as $rawMove) {
            $name = $rawMove['pokemon_v2_move']['pokemon_v2_movenames'][0]['name'];
            if(isset($rawMove['pokemon_v2_move']['pokemon_v2_machines'][0]['pokemon_v2_item']['pokemon_v2_itemnames'][0]['name'])) {
                $itemName = $rawMove['pokemon_v2_move']['pokemon_v2_machines'][0]['pokemon_v2_item']['pokemon_v2_itemnames'][0]['name'];
            }
            $versionGroup = $rawMove['pokemon_v2_versiongroup']['name'];
            $learnMethod = $rawMove['pokemon_v2_movelearnmethod']['name'];
            if(!array_key_exists($learnMethod,$formattedMoves)) {
                $formattedMoves[$learnMethod] = [];
            }
            if(!array_key_exists($versionGroup,$formattedMoves[$learnMethod])) {
                $formattedMoves[$learnMethod][$versionGroup] = [];
            }
            $formattedMoves[$learnMethod][$versionGroup][] = [
                'name' => $name,
                'item' => $itemName
            ];

        }

        $eggGroups = [];
        foreach ($pokemonData['pokemon_v2_pokemonspecy']['pokemon_v2_pokemonegggroups'] as $eggGroup) {
            $eggGroups[] = $eggGroup['pokemon_v2_egggroup']['pokemon_v2_egggroupnames'][0]['name'];
        }
        return [
            'id' => $pokemonId,
            'groups' => $eggGroups,
            'moves' => $formattedMoves
        ];
    }
}