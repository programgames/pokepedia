<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EvolutionChain;
use App\Entity\Item;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform evolution chains information into entities from pokeapi
class EvolutionChainApi
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

    public function getEvolutionChains(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_evolutionchain {
    pokemon_v2_pokemonspecies {
      name
    }
    pokemon_v2_item {
      name
    }
  }
}
GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $evolutionChains = [];
        foreach ($content['data']['pokemon_v2_evolutionchain'] as $evolutionChain) {
            $evolutionChainEntity = new EvolutionChain();

            foreach ($evolutionChain['pokemon_v2_pokemonspecies'] as $specy) {
                $specyEntity = $this->entityManager->getRepository(PokemonSpecy::class)
                    ->findOneBy(
                        [
                            'name' => $specy['name']
                        ]
                    );
                if ($specyEntity) {
                    $evolutionChainEntity->addPokemonSpecies($specyEntity);
                    $specyEntity->setEvolutionChain($evolutionChainEntity);
                }
            }

            if($evolutionChain['pokemon_v2_item']) {
                $item = $this->entityManager->getRepository(Item::class)
                    ->findOneBy(
                        [
                            'name' => $evolutionChain['pokemon_v2_item']['name']
                        ]
                    );
                $evolutionChainEntity->setBabyTriggerItem($item);
            }

            $evolutionChains[] = $evolutionChainEntity;
        }

        return $evolutionChains;
    }
}
