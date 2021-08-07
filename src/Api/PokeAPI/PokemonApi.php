<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Pokemon;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform pokemon information into entities from pokeapi
class PokemonApi
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

    public function getPokemons(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemon {
    name
    is_default
    base_experience
    height
    order
    weight
    pokemon_v2_pokemonspecy {
      name
    }
  }
}
GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokemons = [];
        foreach ($content['data']['pokemon_v2_pokemon'] as $pokemon) {
            $pokemonEntity = new Pokemon();
            $pokemonEntity->setName($pokemon['name']);
            $pokemonEntity->setIsDefault($pokemon['is_default']);
            $pokemonEntity->setHeight($pokemon['height']);
            $pokemonEntity->setPokemonOrder($pokemon['order']);
            $pokemonEntity->setBaseExperience($pokemon['base_experience']);
            $pokemonEntity->setWeight($pokemon['weight']);
            /** @var PokemonSpecy $specy */
            $specy = $this->entityManager->getRepository(PokemonSpecy::class)->findOneBy(
                [
                    'name' => $pokemon['pokemon_v2_pokemonspecy']['name'],
                ]
            );
            if ($specy) {
                $pokemonEntity->setPokemonSpecy($specy);
            }
            $pokemons[] = $pokemonEntity;
        }

        return $pokemons;
    }
}
