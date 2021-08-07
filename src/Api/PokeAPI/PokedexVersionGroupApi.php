<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\EggGroup;
use App\Entity\ItemCategory;
use App\Entity\ItemPocket;
use App\Entity\Pokedex;
use App\Entity\PokedexVersionGroup;
use App\Entity\Type;
use App\Entity\TypeName;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class PokedexVersionGroupApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $em)
    {
        $this->client = $client;
        $this->em = $em;
    }

    public function getPokedexVersionGroups(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokedexversiongroup {
    pokemon_v2_pokedex {
      name
    }
    pokemon_v2_versiongroup {
      name
    }
  }
}

GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokedexVersionGroups = [];
        foreach ($content['data']['pokemon_v2_pokedexversiongroup'] as $pokedexVersionGroup) {
            $pokedexVersionGroupEntity = new PokedexVersionGroup();
            $pokedex = $this->em->getRepository(Pokedex::class)
                ->findOneBy(["name" => $pokedexVersionGroup['pokemon_v2_pokedex']['name']]);
            $pokedexVersionGroupEntity->setPokedex($pokedex);
            $versionGroup = $this->em->getRepository(VersionGroup::class)
                ->findOneBy(["name" => $pokedexVersionGroup['pokemon_v2_versiongroup']['name']]);
            $pokedexVersionGroupEntity->setVersionGroup($versionGroup);
            $pokedexVersionGroups[] = $pokedexVersionGroupEntity;
        }

        return $pokedexVersionGroups;
    }
}
