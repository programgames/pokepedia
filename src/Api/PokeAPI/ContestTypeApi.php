<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\ContestType;
use App\Entity\EggGroup;
use App\Entity\GrowthRate;

//extract and transform egg information into entities from pokeapi
class ContestTypeApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getContestTypes(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_contesttype {
    name
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $contestTypes = [];
        foreach ($content['data']['pokemon_v2_contesttype'] as $contestType) {
            $contestTypeEntity = new ContestType();
            $contestTypeEntity->setName($contestType['name']);
            $contestTypes[] = $contestTypeEntity;
        }

        return $contestTypes;
    }
}
