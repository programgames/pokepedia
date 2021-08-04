<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\MoveDamageClass;

class MoveDamageClassApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getMoveDam(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_movedamageclass {
    name
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $moveDamageClasses = [];
        foreach ($content['data']['pokemon_v2_movedamageclass'] as $moveDamageClass) {
            $moveDamageClassEntity = new MoveDamageClass();
            $moveDamageClassEntity->setName($moveDamageClass['name']);
            $moveDamageClasses[] = $moveDamageClassEntity;
        }

        return $moveDamageClasses;
    }
}