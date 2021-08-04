<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\ItemCategory;
use App\Entity\ItemPocket;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class ItemCategoryApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    public function getItemCategory(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_itemcategory {
    name
    pokemon_v2_itempocket {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $itemCategories = [];
        foreach ($content['data']['pokemon_v2_itemcategory'] as $itemCategory) {
            $itemCategoryEntity = new ItemCategory();
            $itemCategoryEntity->setName($itemCategory['name']);
            $itemPocket = $this->em->getRepository(ItemPocket::class)
                ->findOneBy(['name' => $itemCategory['pokemon_v2_itempocket']['name']]);
            $itemCategoryEntity->setItemPocket($itemPocket);
            $itemCategories[] = $itemCategoryEntity;
        }

        return $itemCategories;
    }
}
