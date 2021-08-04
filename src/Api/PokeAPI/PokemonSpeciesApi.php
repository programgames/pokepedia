<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\GrowthRate;
use App\Entity\PokemonColor;
use App\Entity\PokemonHabitat;
use App\Entity\PokemonShape;
use App\Entity\PokemonSpecy;
use Doctrine\ORM\EntityManagerInterface;

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
    base_happiness
    capture_rate
    forms_switchable
    gender_rate
    has_gender_differences
    hatch_counter
    is_baby
    is_legendary
    is_mythical
    name
    order
    pokemon_v2_generation {
      name
    }
    pokemon_v2_growthrate {
      name
    }
    pokemon_v2_pokemonhabitat {
      name
    }
    pokemon_v2_pokemoncolor {
      name
    }
    pokemon_v2_pokemonshape {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokemonSpecies = [];
        foreach ($content['data']['pokemon_v2_pokemonspecies'] as $pokemonspecy) {
            $pokemonSpecyEntity = new PokemonSpecy();
            $pokemonSpecyEntity->setName($pokemonspecy['name']);
            $pokemonSpecyEntity->setPokemonSpecyOrder($pokemonspecy['order']);
            $pokemonSpecyEntity->setBaseHapiness($pokemonspecy['base_happiness']);
            $pokemonSpecyEntity->setCaptureRate($pokemonspecy['capture_rate']);
            $pokemonSpecyEntity->setFormsSwitchable($pokemonspecy['forms_switchable']);
            $pokemonSpecyEntity->setGenderRate($pokemonspecy['gender_rate']);
            $pokemonSpecyEntity->setHasGenderDifferences($pokemonspecy['has_gender_differences']);
            $pokemonSpecyEntity->setHatchCounter($pokemonspecy['hatch_counter']);
            $pokemonSpecyEntity->setIsBaby($pokemonspecy['is_baby']);
            $pokemonSpecyEntity->setIsLegendary($pokemonspecy['is_legendary']);
            $pokemonSpecyEntity->setIsMythical($pokemonspecy['is_mythical']);

            $generation = $this->entityManager->getRepository(Generation::class)
                ->findOneBy(
                    [
                        'name' => $pokemonspecy['pokemon_v2_generation']['name']
                    ]
                );
            $pokemonSpecyEntity->setGeneration($generation);

            $growthRate = $this->entityManager->getRepository(GrowthRate::class)
                ->findOneBy(
                    [
                        'name' => $pokemonspecy['pokemon_v2_growthrate']['name']
                    ]
                );
            $pokemonSpecyEntity->setGrowthRate($growthRate);

            $shape = $this->entityManager->getRepository(PokemonShape::class)
                ->findOneBy(
                    [
                        'name' => $pokemonspecy['pokemon_v2_pokemonshape']['name']
                    ]
                );
            $pokemonSpecyEntity->setPokemonShape($shape);

            if ($pokemonspecy['pokemon_v2_pokemonhabitat']) {
                $habitat = $this->entityManager->getRepository(PokemonHabitat::class)
                    ->findOneBy(
                        [
                            'name' => $pokemonspecy['pokemon_v2_pokemonhabitat']['name']
                        ]
                    );
                $pokemonSpecyEntity->setPokemonHabitat($habitat);
            }

            $color = $this->entityManager->getRepository(PokemonColor::class)
                ->findOneBy(
                    [
                        'name' => $pokemonspecy['pokemon_v2_pokemoncolor']['name']
                    ]
                );
            $pokemonSpecyEntity->setPokemonColor($color);

            $pokemonSpecies[] = $pokemonSpecyEntity;
        }

        return $pokemonSpecies;
    }

    public function getEvolutionInfos(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemonspecies {
   	id    
    name
    evolves_from_species_id
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $species = $content['data']['pokemon_v2_pokemonspecies'];

        $pokemonSpecies = [];
        foreach ($content['data']['pokemon_v2_pokemonspecies'] as $pokemonspecy) {
            if ($pokemonspecy['evolves_from_species_id']) {
                /** @var PokemonSpecy $to */
                $to = $this->entityManager->getRepository(PokemonSpecy::class)
                    ->findOneBy(["name" => $pokemonspecy["name"]]);

                $fromName = $this->findFromPokemonName($species, $pokemonspecy['evolves_from_species_id']);
                $from = $this->entityManager->getRepository(PokemonSpecy::class)
                    ->findOneBy(["name" => $fromName]);

                $to->setEvolveFrom($from);
                $pokemonSpecies[] = $to;

            }
        }

        return $pokemonSpecies;
    }

    private function findFromPokemonName(array $species, int $id): string
    {
        foreach ($species as $specy) {
            if ($specy['id'] === $id) {
                return $specy['name'];
            }
        }
        throw new \RuntimeException(sprintf('Specy id %s not found', $id));
    }
}
