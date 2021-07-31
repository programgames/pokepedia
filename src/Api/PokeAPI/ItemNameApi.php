<?php


namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use App\Entity\ItemName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'itemname'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $itemNames = [];
        foreach ($json['data']['pokemon_v2_itemname'] as $itemName) {
            $itemNameEntity = new ItemName();
            $itemNameEntity->setName($itemName['name']);
            $itemNameEntity->setLanguage($itemName['language_id']);

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
