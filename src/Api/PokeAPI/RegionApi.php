<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\Region;

//extract and transform generation information into entities from pokeapi
class RegionApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getRegions(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_region {
    name
  }
}

GRAPHQL;



        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $regions = [];
        foreach ($content['data']['pokemon_v2_region'] as $region) {
            $regionEntity = new Region();
            $regionEntity->setName($region['name']);
            $regions[] = $regionEntity;
        }

        return $regions;
    }
}
