<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;

//extract and transform generation information into entities from pokeapi
class GenerationApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getGenerations(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_generation {
    name
    id
  }
}

GRAPHQL;



        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $generations = [];
        foreach ($content['data']['pokemon_v2_generation'] as $generation) {
            $generationEntity = new Generation();
            $generationEntity->setName($generation['name']);
            $generationEntity->setGenerationIdentifier($generation['id']);
            $generations[] = $generationEntity;
        }

        return $generations;
    }
}
