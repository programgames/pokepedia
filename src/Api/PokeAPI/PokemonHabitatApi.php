<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\PokemonHabitat;

//extract and transform egg information into entities from pokeapi
class PokemonHabitatApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getPokemonHabitats(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonhabitat {
    name
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $shapes = [];
        foreach ($content['data']['pokemon_v2_pokemonhabitat'] as $shape) {
            $shapeEntity = new PokemonHabitat();
            $shapeEntity->setName($shape['name']);
            $growthRates[] = $shapeEntity;
        }

        return $shapes;
    }
}
