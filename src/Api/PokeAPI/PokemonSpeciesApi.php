<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\PokemonSpecy;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class PokemonSpeciesApi
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

    public function getPokemonSpecies(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonspecies {
    order
    name
    pokemon_v2_pokemonegggroups {
      pokemon_v2_egggroup {
        name
      }
    }
  }
}

GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'species'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $pokemonSpecies = [];
        foreach ($json['data']['pokemon_v2_pokemonspecies'] as $pokemonspecy) {
            $pokemonSpecyEntity = new PokemonSpecy();
            $pokemonSpecyEntity->setName($pokemonspecy['name']);
            $pokemonSpecyEntity->setPokemonSpeciesOrder($pokemonspecy['order']);
            foreach ($pokemonspecy['pokemon_v2_pokemonegggroups'] as $eggGroup) {
                $eggGroupEntity = $this->entityManager->getRepository(EggGroup::class)
                    ->findOneBy(
                        [
                            'name' => $eggGroup['pokemon_v2_egggroup']['name']
                        ]
                    );
                if(!$eggGroupEntity) {
                    continue;
                }
                $pokemonSpecyEntity->addEggGroup($eggGroupEntity);
            }
            $pokemonSpecies[] = $pokemonSpecyEntity;
        }

        return $pokemonSpecies;
    }
}
