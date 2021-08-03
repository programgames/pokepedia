<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use App\Entity\ItemName;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform item names information into entities from pokeapi
class ItemNameApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getItemNames(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_itemname {
    name
    language_id
    pokemon_v2_item {
      name
    }
  }
}


GRAPHQL;


        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $itemNames = [];
        foreach ($content['data']['pokemon_v2_itemname'] as $itemName) {
            $itemNameEntity = new ItemName();
            $itemNameEntity->setName($itemName['name']);
            $itemNameEntity->setLanguage($itemName['language_id']);

            /** @var Item $item */
            $item = $this->entityManager->getRepository(Item::class)
                ->findOneBy(
                    [
                        'name' => $itemName['pokemon_v2_item']['name']
                    ]
                );
            $itemNameEntity->setItem($item);
            $itemNames[] = $itemNameEntity;
        }

        return $itemNames;
    }
}
