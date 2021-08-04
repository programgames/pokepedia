<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\PokemonType;
use App\Entity\PokemonTypePast;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

class PokemonTypePastApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getPokemonTypePast(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemontypepast {
    slot
    pokemon_v2_type {
      name
    }
    pokemon_v2_pokemon {
      name
    }
    pokemon_v2_generation {
      name
    }
  }
}
GRAPHQL;

        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokemonTypesPast = [];
        foreach ($content['data']['pokemon_v2_pokemontypepast'] as $pokemonTypePast) {
            $pokemonTypePastEntity = new PokemonTypePast();
            $pokemonTypePastEntity->setSlot($pokemonTypePast['slot']);
            $type = $this->entityManager->getRepository(Type::class)->findOneBy(
                [
                    'name' => $pokemonTypePast['pokemon_v2_type']['name']
                ]
            );
            $pokemon = $this->entityManager->getRepository(Pokemon::class)->findOneBy(
                [
                    'name' => $pokemonTypePast['pokemon_v2_pokemon']['name']
                ]
            );
            $generation = $this->entityManager->getRepository(Generation::class)->findOneBy(
                [
                    'name' => $pokemonTypePast['pokemon_v2_generation']['name']
                ]
            );
            $pokemonTypePastEntity->setPokemon($pokemon);
            $pokemonTypePastEntity->setType($type);
            $pokemonTypePastEntity->setGeneration($generation);
            $pokemonTypesPast[] = $pokemonTypePastEntity;
        }

        return $pokemonTypesPast;
    }
}