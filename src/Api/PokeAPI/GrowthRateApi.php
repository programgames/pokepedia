<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\GrowthRate;

//extract and transform egg information into entities from pokeapi
class GrowthRateApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getGrowthRates(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_growthrate {
    formula
    name
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $growthRates = [];
        foreach ($content['data']['pokemon_v2_growthrate'] as $growthRate) {
            $growthRateEntity = new GrowthRate();
            $growthRateEntity->setName($growthRate['name']);
            $growthRateEntity->setFormula($growthRate['formula']);
            $growthRates[] = $growthRateEntity;
        }

        return $growthRates;
    }
}
