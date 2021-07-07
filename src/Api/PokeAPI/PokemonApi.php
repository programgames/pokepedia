<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Pokemon;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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
    order
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
            $pokemonEntity->setPokemonOrder($pokemon['order']);
            $pokemonEntity->setPokemonIdentifier($pokemon['id']);
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
        if ($pokemon['id'] >= 1 && $pokemon['id'] <= 898) {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(false);
        } elseif (preg_match('/-galar$/', $pokemon['name'], $matches)) {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(true);
            $pokemonEntity->setIsAlola(false);
        } elseif (preg_match('/-alola$/', $pokemon['name'], $matches)) {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(true);
        } elseif ($pokemon['name'] === 'kyurem-black') {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(false);
            $pokemonEntity->setSpecificName('Kyurem_Noir');
        } elseif ($pokemon['name'] === 'kyurem-white') {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(false);
            $pokemonEntity->setSpecificName('Kyurem_Blanc');
        } elseif ($pokemon['name'] === 'necrozma-dusk') {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(false);
            $pokemonEntity->setSpecificName('Necrozma_Crinière_du_Couchant');
        } elseif ($pokemon['name'] === 'necrozma-dawn') {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(false);
            $pokemonEntity->setSpecificName('Necrozma_Ailes_de_l\'Aurore');
        } elseif ($pokemon['name'] === 'necrozma-ultra') {
            $pokemonEntity->setToImport(true);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(false);
            $pokemonEntity->setSpecificName('Ultra-Necrozma');
        } else {
            $pokemonEntity->setToImport(false);
            $pokemonEntity->setIsGalar(false);
            $pokemonEntity->setIsAlola(false);
        }
        return $pokemonEntity;
    }

}
