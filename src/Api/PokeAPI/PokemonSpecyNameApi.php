<?php


namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\PokemonSpecy;
use App\Entity\SpecyName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'specyname'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $specyNames = [];
        foreach ($json['data']['pokemon_v2_pokemonspeciesname'] as $specyName) {
            $specyNameEntity = new SpecyName();
            $specyNameEntity->setName($specyName['name']);
            $specyNameEntity->setLanguage($specyName['language_id']);
            $move = $this->entityManager->getRepository(PokemonSpecy::class)->findOneBy(
                [
                    'name' => $specyName['pokemon_v2_pokemonspecy']['name']
                ]
            );
            $specyNameEntity->setPokemonSpecy($move);
            $specyNames[] = $specyNameEntity;
        }

        return $specyNames;
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

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s-english', 'specyname'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $specyNames = [];
        foreach ($json['data']['pokemon_v2_pokemonspeciesname'] as $specyName) {
            $specyNameEntity = new SpecyName();
            $specyNameEntity->setName($specyName['name']);
            $specyNameEntity->setLanguage($specyName['language_id']);
            $move = $this->entityManager->getRepository(PokemonSpecy::class)->findOneBy(
                [
                    'name' => $specyName['pokemon_v2_pokemonspecy']['name']
                ]
            );
            $specyNameEntity->setPokemonSpecy($move);
            $specyNames[] = $specyNameEntity;
        }

        return $specyNames;
    }
}
