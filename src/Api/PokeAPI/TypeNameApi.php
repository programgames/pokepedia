<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\ItemCategory;
use App\Entity\ItemPocket;
use App\Entity\Type;
use App\Entity\TypeName;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class TypeNameApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    public function getTypeNames(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_typename(where: {language_id: {_in: [5, 9]}}) {
    name
    language_id
    pokemon_v2_type {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $typeNames = [];
        foreach ($content['data']['pokemon_v2_typename'] as $typeName) {
            $typeNameEntity = new TypeName();
            $typeNameEntity->setName($typeName['name']);
            $typeNameEntity->setLanguage($typeName['language_id']);
            $typeEntity = $this->em->getRepository(Type::class)
                ->findOneBy(["name" => $typeName['pokemon_v2_type']['name']]);
            $typeNameEntity->setType($typeEntity);
            $typeNames[] = $typeNameEntity;
        }

        return $typeNames;
    }
}
