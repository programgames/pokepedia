<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class VersionGroupApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getVersionGroups(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_versiongroup {
    name
    order
    pokemon_v2_generation {
      name
    }
  }
}
GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'versiongroup'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $versionGroups = [];
        foreach ($json['data']['pokemon_v2_versiongroup'] as $versiongroup) {
            $versionGroupEntity = new VersionGroup();
            $versionGroupEntity->setName($versiongroup['name']);
            $generation = $this->entityManager->getRepository(Generation::class)->findOneBy(
                [
                    'name' => $versiongroup['pokemon_v2_generation']['name']
                ]
            );
            $versionGroupEntity->setGeneration($generation);
            $versionGroupEntity->setVersionGroupOrder($versiongroup['order']);
            $versionGroups[] = $versionGroupEntity;
        }

        return $versionGroups;
    }
}