<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Pokemon;
use App\Entity\PokemonColor;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform pokemon information into entities from pokeapi
class PokemonColorApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    /**
     * PokemonSpeciesApi constructor.
     * @param PokeAPIGraphQLClient $client
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getPokemonColors(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemoncolor {
    name
  }
}
GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $colors = [];
        foreach ($content['data']['pokemon_v2_pokemoncolor'] as $color) {
            $colorEntity = new PokemonColor();
            $colorEntity->setName($color['name']);
            $colors[] = $colorEntity;
        }

        return $colors;
    }
}
