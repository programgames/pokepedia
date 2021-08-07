<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\ItemCategory;
use App\Entity\ItemPocket;
use App\Entity\Pokedex;
use App\Entity\PokedexVersionGroup;
use App\Entity\Pokemon;
use App\Entity\PokemonGameIndex;
use App\Entity\Type;
use App\Entity\TypeName;
use App\Entity\Version;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class PokemonGameIndexApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    public function getPokemonGameIndexes(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokemongameindex {
    game_index
    pokemon_v2_version {
      name
    }
    pokemon_v2_pokemon {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $gameIndexes = [];
        foreach ($content['data']['pokemon_v2_pokemongameindex'] as $gameIndex) {
            $gameIndexEntity = new PokemonGameIndex();
            $version = $this->em->getRepository(Version::class)
                ->findOneBy(["name" => $gameIndex['pokemon_v2_version']['name']]);
            $gameIndexEntity->setVersion($version);
            $pokemon = $this->em->getRepository(Pokemon::class)
                ->findOneBy(["name" => $gameIndex['pokemon_v2_pokemon']['name']]);
            $gameIndexEntity->setPokemon($pokemon);
            $gameIndexEntity->setGameIndex($gameIndex['game_index']);
            $gameIndexes[] = $gameIndexEntity;
        }

        return $gameIndexes;
    }
}
