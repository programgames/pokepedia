<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\PokemonSpecy;
use App\Entity\SpecyName;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform pokemon species names information into entities from pokeapi
class PokemonSpecyNameApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getFrenchSpecyNames(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonspeciesname(where: {language_id: {_eq: 5}}) {
    name
    language_id
    pokemon_v2_pokemonspecy {
      name
    }
  }
}



GRAPHQL;

        return $this->getSpecyNamesByQuery($query);
    }

    public function getEnglishSpecyNames(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonspeciesname(where: {language_id: {_eq: 9}}) {
    name
    language_id
    pokemon_v2_pokemonspecy {
      name
    }
  }
}



GRAPHQL;

        return $this->getSpecyNamesByQuery($query);
    }

    private function getSpecyNamesByQuery(string $query)
    {
        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $specyNames = [];
        foreach ($content['data']['pokemon_v2_pokemonspeciesname'] as $specyName) {
            $specyNameEntity = new SpecyName();
            $specyNameEntity->setName($specyName['name']);
            $specyNameEntity->setLanguage($specyName['language_id']);
            /** @var PokemonSpecy $specy */
            $specy = $this->entityManager->getRepository(PokemonSpecy::class)->findOneBy(
                [
                    'name' => $specyName['pokemon_v2_pokemonspecy']['name']
                ]
            );
            $specyNameEntity->setPokemonSpecy($specy);
            $specyNames[] = $specyNameEntity;
        }

        return $specyNames;
    }
}
