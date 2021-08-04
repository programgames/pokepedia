<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;

//extract and transform egg information into entities from pokeapi
class EggGroupApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getEggGroups(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_egggroup {
    name
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $eggGroups = [];
        foreach ($content['data']['pokemon_v2_egggroup'] as $eggGroup) {
            $eggGroupEntity = new EggGroup();
            $eggGroupEntity->setName($eggGroup['name']);
            $eggGroups[] = $eggGroupEntity;
        }

        return $eggGroups;
    }
}
