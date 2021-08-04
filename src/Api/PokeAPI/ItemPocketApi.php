<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\ItemPocket;

//extract and transform egg information into entities from pokeapi
class ItemPocketApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getItemPockets(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_itempocket {
    name
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $itemPockets = [];
        foreach ($content['data']['pokemon_v2_itempocket'] as $itemPocket) {
            $itemPocketEntity = new ItemPocket();
            $itemPocketEntity->setName($itemPocket['name']);
            $itemPockets[] = $itemPocketEntity;
        }

        return $itemPockets;
    }
}
