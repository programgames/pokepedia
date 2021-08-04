<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use App\Entity\ItemFlingEffect;

class ItemFlingEffectApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getFlyingEffects(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_itemflingeffect {
    name
  }
}

GRAPHQL;


        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $effects = [];
        foreach ($content['data']['pokemon_v2_itemflingeffect'] as $effect) {
            $effectEntity = new ItemFlingEffect();
            $effectEntity->setName($effect['name']);
            $effects[] = $effectEntity;
        }

        return $effects;
    }
}
