<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use App\Entity\ItemCategory;
use App\Entity\ItemFlingEffect;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform item information into entities from pokeapi
class ItemApi
{
    private PokeAPIGraphQLClient $client;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em,PokeAPIGraphQLClient $client)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function getItems(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_item {
    fling_power
    cost
    name
    pokemon_v2_itemcategory {
      name
    }
    pokemon_v2_itemflingeffect {
      name
    }
  }
}


GRAPHQL;


        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $items = [];
        foreach ($content['data']['pokemon_v2_item'] as $item) {
            $itemEntity = new Item();
            $itemCategory = $this->em->getRepository(ItemCategory::class)
                ->findOneBy(['name' => $item['pokemon_v2_itemcategory']['name']]);
            $itemEntity->setItemCategory($itemCategory);
            if( $item['pokemon_v2_itemflingeffect'] ) {
                $effect = $this->em->getRepository(ItemFlingEffect::class)
                    ->findOneBy(['name' => $item['pokemon_v2_itemflingeffect']['name']]);
                $itemEntity->setItemFlingEffect($effect);
            }
            $itemEntity->setName($item['name']);
            $itemEntity->setCost($item['cost']);
            $itemEntity->setFlingPower($item['fling_power']);
            $items[] = $itemEntity;
        }

        return $items;
    }
}
