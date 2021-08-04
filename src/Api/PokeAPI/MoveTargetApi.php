<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Move;
use App\Entity\MoveTarget;

//extract and transform move move information into entities from pokeapi
class MoveTargetApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getMoveTargets(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_movetarget {
    name
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $targets = [];
        foreach ($content['data']['pokemon_v2_movetarget'] as $target) {
            $targetEntity = new MoveTarget();
            $targetEntity->setName($target['name']);
            $targets[] = $targetEntity;
        }

        return $targets;
    }
}
