<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Generation;
use App\Entity\Move;
use App\Entity\MoveDamageClass;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonType;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform move names information into entities from pokeapi
class PokemonTypeApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getPokemonTypes(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemontype {
    slot
    pokemon_v2_type {
      name
    }
    pokemon_v2_pokemon {
      name
    }
  }
}
GRAPHQL;

        $content =  $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokemonTypes = [];
        foreach ($content['data']['pokemon_v2_pokemontype'] as $pokemonType) {
            $pokemonTypeEntity = new PokemonType();
            $pokemonTypeEntity->setSlot($pokemonType['slot']);
            $type = $this->entityManager->getRepository(Type::class)->findOneBy(
                [
                    'name' => $pokemonType['pokemon_v2_type']['name']
                ]
            );
            $pokemon = $this->entityManager->getRepository(Pokemon::class)->findOneBy(
                [
                    'name' => $pokemonType['pokemon_v2_pokemon']['name']
                ]
            );
            $pokemonTypeEntity->setPokemon($pokemon);
            $pokemonTypeEntity->setType($type);
            $pokemonTypes[] = $pokemonTypeEntity;
        }

        return $pokemonTypes;
    }
}
