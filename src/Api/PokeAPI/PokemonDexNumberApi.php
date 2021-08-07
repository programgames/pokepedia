<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\ItemCategory;
use App\Entity\ItemPocket;
use App\Entity\Pokedex;
use App\Entity\PokedexVersionGroup;
use App\Entity\PokemonDexNumber;
use App\Entity\PokemonSpecy;
use App\Entity\Type;
use App\Entity\TypeName;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class PokemonDexNumberApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    public function getPokemonDexNumbersApi(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemondexnumber {
    pokedex_number
    pokemon_v2_pokedex {
      name
    }
    pokemon_v2_pokemonspecy {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $dexNumbers = [];
        foreach ($content['data']['pokemon_v2_pokemondexnumber'] as $dexNumber) {
            $dexNumberEntity = new PokemonDexNumber();
            $pokedex = $this->em->getRepository(Pokedex::class)
                ->findOneBy(["name" => $dexNumber['pokemon_v2_pokedex']['name']]);
            $dexNumberEntity->setPokedex($pokedex);
            $specy = $this->em->getRepository(PokemonSpecy::class)
                ->findOneBy(["name" => $dexNumber['pokemon_v2_pokemonspecy']['name']]);
            $dexNumberEntity->setPokemonSpecy($specy);
            $dexNumberEntity->setPokedexNumber($dexNumber['pokedex_number']);
            $dexNumbers[] = $dexNumberEntity;
        }

        return $dexNumbers;
    }
}
