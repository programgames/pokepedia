<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\ContestEffect;
use App\Entity\ContestType;
use App\Entity\EggGroup;
use App\Entity\GrowthRate;
use App\Entity\Pokedex;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform egg information into entities from pokeapi
class PokedexApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $em;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->client = $client;
    }

    public function getPokedexs(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_pokedex {
    name
    is_main_series
    pokemon_v2_region {
      name
    }
  }
}


GRAPHQL;

        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $pokedexs = [];
        foreach ($content['data']['pokemon_v2_pokedex'] as $pokedex) {
            $pokedexEntity = new Pokedex();
            $pokedexEntity->setName($pokedex['name']);
            $pokedexEntity->setIsMainSeries($pokedex['is_main_series']);
            if($pokedex['pokemon_v2_region']) {
                $region = $this->em->getRepository(Region::class)
                    ->findOneBy(['name' => $pokedex['pokemon_v2_region']['name']]);
                $pokedexEntity->setRegion($region);
            }
            $pokedexs[] = $pokedexEntity;
        }

        return $pokedexs;
    }
}
