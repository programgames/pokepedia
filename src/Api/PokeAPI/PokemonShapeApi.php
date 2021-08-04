<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\GrowthRate;
use App\Entity\PokemonShape;

//extract and transform egg information into entities from pokeapi
class PokemonShapeApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getPokemonShapes(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonshape {
    name
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $shapes = [];
        foreach ($content['data']['pokemon_v2_pokemonshape'] as $shape) {
            $shapeEntity = new PokemonShape();
            $shapeEntity->setName($shape['name']);
            $shapes[] = $shapeEntity;
        }

        return $shapes;
    }
}
