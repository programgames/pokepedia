<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\ContestEffect;
use App\Entity\ContestType;
use App\Entity\EggGroup;
use App\Entity\Generation;
use App\Entity\GrowthRate;
use App\Entity\PokemonForm;
use App\Entity\PokemonFormGeneration;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class PokemonFormGenerationApi
{
    private PokeAPIGraphQLClient $client;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function getPokemonFormCategories(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonformgeneration {
    game_index
    pokemon_v2_generation {
      name
    }
    pokemon_v2_pokemonform {
      name
    }
  }
}


GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $formGenerations = [];
        foreach ($content['data']['pokemon_v2_pokemonformgeneration'] as $pokemonFormGeneration) {
            $pokemonFormGenerationEntity = new PokemonFormGeneration();
            $pokemonFormGenerationEntity->setGameIndex($pokemonFormGeneration['game_index']);
            $generation = $this->em->getRepository(Generation::class)
                ->findOneBy(['name' => $pokemonFormGeneration['pokemon_v2_generation']['name']]
                );
            $pokemonFormGenerationEntity->setGeneration($generation);
            $pokemonForm = $this->em->getRepository(PokemonForm::class)
                ->findOneBy(['name' => $pokemonFormGeneration['pokemon_v2_pokemonform']['name']]);
            $pokemonFormGenerationEntity->setPokemonForm($pokemonForm);
            $formGenerations[] = $pokemonFormGenerationEntity;
        }

        return $formGenerations;
    }
}
