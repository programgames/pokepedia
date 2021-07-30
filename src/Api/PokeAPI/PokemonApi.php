<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Pokemon;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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
    pokemon_v2_pokemonspecy {
      name
    }
    id
  }
}
GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'pokemon'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $pokemons = [];
        foreach ($json['data']['pokemon_v2_pokemon'] as $pokemon) {
            $pokemonEntity = new Pokemon();
            $pokemonEntity->setName($pokemon['name']);
            $pokemonEntity->setIsDefault($pokemon['is_default']);
            $pokemonEntity = $this->setImportInformations($pokemonEntity, $pokemon);
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

    private function setImportInformations(Pokemon $pokemonEntity, $pokemon): Pokemon
    {
        if (preg_match('/-galar$/', $pokemon['name'], $matches)) {
            $pokemonEntity->setIsGalar(true);
        } elseif (preg_match('/-alola$/', $pokemon['name'], $matches)) {
            $pokemonEntity->setIsAlola(true);
        }
        return $pokemonEntity;
    }

}
