<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

//extract and transform item information into entities from pokeapi
class ItemApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
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

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'item'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $items = [];
        foreach ($json['data']['pokemon_v2_item'] as $item) {
            $itemEntity = new Item();
            $itemEntity->setName($item['name']);
            $items[] = $itemEntity;
        }

        return $items;
    }
}
