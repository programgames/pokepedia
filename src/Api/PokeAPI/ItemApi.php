<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;

//extract and transform item information into entities from pokeapi
class ItemApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getItems(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_item {
    name
  }
}

GRAPHQL;


        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $items = [];
        foreach ($content['data']['pokemon_v2_item'] as $item) {
            $itemEntity = new Item();
            $itemEntity->setName($item['name']);
            $items[] = $itemEntity;
        }

        return $items;
    }
}
