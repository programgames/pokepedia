<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Move;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

//extract and transform move move information into entities from pokeapi
class MoveApi
{
    private PokeAPIGraphQLClient $client;

    public function __construct(PokeAPIGraphQLClient $client)
    {
        $this->client = $client;
    }

    public function getMoves(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_move {
    name
  }
}

GRAPHQL;

        $content =$this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $moves = [];
        foreach ($content['data']['pokemon_v2_move'] as $move) {
            $moveEntity = new Move();
            $moveEntity->setName($move['name']);
            $moves[] = $moveEntity;
        }

        return $moves;
    }
}
