<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\ContestEffect;
use App\Entity\ContestType;
use App\Entity\EggGroup;
use App\Entity\GrowthRate;

//extract and transform egg information into entities from pokeapi
class ContestEffectApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getContestEffects(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_contesteffect {
    jam
    appeal
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $contestEffects = [];
        foreach ($content['data']['pokemon_v2_contesteffect'] as $contestEffect) {
            $contestEffectEntity = new ContestEffect();
            $contestEffectEntity->setJam($contestEffect['jam']);
            $contestEffectEntity->setAppeal($contestEffect['appeal']);
            $contestEffects[] = $contestEffectEntity;
        }

        return $contestEffects;
    }
}
