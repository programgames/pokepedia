<?php


namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\Generation;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

//extract and transform pokemon species information into entities from pokeapi
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
    name
    pokemon_v2_pokemondexnumbers(where: {pokemon_v2_pokedex: {name: {_eq: "national"}}}) {
      pokedex_number
    }
    pokemon_v2_pokemonegggroups {
      pokemon_v2_egggroup {
        name
      }
    }
    pokemon_v2_generation {
      name
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
            $pokemonSpecyEntity->setPokemonSpeciesOrder($pokemonspecy['pokemon_v2_pokemondexnumbers'][0]['pokedex_number']);
            foreach ($pokemonspecy['pokemon_v2_pokemonegggroups'] as $eggGroup) {
                $eggGroupEntity = $this->entityManager->getRepository(EggGroup::class)
                    ->findOneBy(
                        [
                            'name' => $eggGroup['pokemon_v2_egggroup']['name']
                        ]
                    );
                if (!$eggGroupEntity) {
                    continue;
                }
                $pokemonSpecyEntity->addEggGroup($eggGroupEntity);
            }
            $generation = $this->entityManager->getRepository(Generation::class)
                ->findOneBy(
                    [
                        'name' => $pokemonspecy['pokemon_v2_generation']['name']
                    ]
                );
            $pokemonSpecyEntity->setGeneration($generation);
            $pokemonSpecies[] = $pokemonSpecyEntity;
        }

        return $pokemonSpecies;
    }
}
