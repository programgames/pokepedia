<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EvolutionChain;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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
  }
}
GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'evolitonchain'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $evolutionChains = [];
        foreach ($json['data']['pokemon_v2_evolutionchain'] as $evolutionChain) {
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
                }
            }

            $evolutionChains[] = $evolutionChainEntity;
        }

        return $evolutionChains;
    }
}
