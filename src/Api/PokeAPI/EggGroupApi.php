<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

//extract and transform egg information into entities from pokeapi
class EggGroupApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getPokemons(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_egggroup {
    name
  }
}

GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'egggroup'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $eggGroups = [];
        foreach ($json['data']['pokemon_v2_egggroup'] as $eggGroup) {
            $eggGroupEntity = new EggGroup();
            $eggGroupEntity->setName($eggGroup['name']);
            $eggGroups[] = $eggGroupEntity;
        }

        return $eggGroups;
    }
}
