<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\Region;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform generation information into entities from pokeapi
class GenerationApi
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

    public function getGenerations(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_generation {
    name
    id
    pokemon_v2_region {
      name
    }
  }
}

GRAPHQL;



        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $generations = [];
        foreach ($content['data']['pokemon_v2_generation'] as $generation) {
            $generationEntity = new Generation();
            $generationEntity->setName($generation['name']);
            $generationEntity->setGenerationIdentifier($generation['id']);
            $region = $this->em->getRepository(Region::class)
                ->findOneBy(['name' => $generation['pokemon_v2_region']['name']]);
            $generationEntity->setRegion($region);
            $generations[] = $generationEntity;
        }

        return $generations;
    }
}
