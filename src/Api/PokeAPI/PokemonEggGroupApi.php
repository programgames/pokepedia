<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\EvolutionChain;
use App\Entity\Item;
use App\Entity\PokemonEggGroup;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform evolution chains information into entities from pokeapi
class PokemonEggGroupApi
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

    public function getPokemonEggGroups(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonegggroup {
    pokemon_v2_egggroup {
      name
    }
    pokemon_v2_pokemonspecy {
      name
    }
  }
}
GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokemonEggGroups = [];
        foreach ($content['data']['pokemon_v2_pokemonegggroup'] as $pokemonEggGroup) {
            $pokemonEggGroupEntity = new PokemonEggGroup();


            $eggGroup = $this->entityManager->getRepository(EggGroup::class)
                ->findOneBy(
                    [
                        'name' => $pokemonEggGroup['pokemon_v2_egggroup']['name']
                    ]
                );
            $pokemonEggGroupEntity->setEggGroup($eggGroup);

            $specyEntity = $this->entityManager->getRepository(PokemonSpecy::class)
                ->findOneBy(
                    [
                        'name' => $pokemonEggGroup['pokemon_v2_pokemonspecy']['name']
                    ]
                );
            $pokemonEggGroupEntity->setPokemonSpecy($specyEntity);

            $pokemonEggGroups[] = $pokemonEggGroupEntity;
        }

        return $pokemonEggGroups;
    }
}
