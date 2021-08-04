<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\Move;
use App\Entity\MoveDamageClass;
use App\Entity\MoveName;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform move names information into entities from pokeapi
class TypeApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getTypes(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_type {
    pokemon_v2_movedamageclass {
      name
    }
    pokemon_v2_generation {
      name
    }
    name
  }
}
GRAPHQL;

        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $types = [];
        foreach ($content['data']['pokemon_v2_type'] as $type) {
            $typeEntity = new Type();
            $typeEntity->setName($type['name']);
            if($type['pokemon_v2_movedamageclass']) {
                $damageClass = $this->entityManager->getRepository(MoveDamageClass::class)->findOneBy(
                    [
                        'name' => $type['pokemon_v2_movedamageclass']['name']
                    ]
                );
                $typeEntity->setMoveDamageClass($damageClass);
            }

            $generation = $this->entityManager->getRepository(Generation::class)->findOneBy(
                [
                    'name' => $type['pokemon_v2_generation']['name']
                ]
            );
            $typeEntity->setGeneration($generation);
            $types[] = $typeEntity;
        }

        return $types;
    }
}
