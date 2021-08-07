<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\ContestEffect;
use App\Entity\ContestType;
use App\Entity\EggGroup;
use App\Entity\GrowthRate;
use App\Entity\Version;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class VersionApi
{
    private PokeAPIGraphQLClient $client;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client,EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function getVersions(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_version {
    name
     pokemon_v2_versiongroup {
       name
    }
  }
}

GRAPHQL;

       $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $versions = [];
        foreach ($content['data']['pokemon_v2_version'] as $version) {
            $versionEntity = new Version();
            $versionEntity->setName($version['name']);
            $versionGroup = $this->em->getRepository(VersionGroup::class)
                ->findOneBy(['name' => $version['pokemon_v2_versiongroup']['name']]);
            $versionEntity->setVersionGroup($versionGroup);
            $versions[] = $versionEntity;
        }

        return $versions;
    }
}
