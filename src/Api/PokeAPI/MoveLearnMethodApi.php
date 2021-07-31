<?php


namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\MoveLearnMethod;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

//extract and transform learn methods information into entities from pokeapi
class MoveLearnMethodApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getMoveLearnMethods(): array
    {
        $query = <<<GRAPHQL
        query MyQuery {
        pokemon_v2_movelearnmethod {
            name
  }
}
GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'movelearnmethod'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $learnMethods = [];

        foreach ($json['data']['pokemon_v2_movelearnmethod'] as $learnMethod) {
            $learnMethodEntity = new MoveLearnMethod();
            $learnMethodEntity->setName($learnMethod['name']);
            $learnMethods[]  = $learnMethodEntity;
        }

        return $learnMethods;
    }
}
