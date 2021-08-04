<?php

namespace App\Api\PokeAPI;

use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\GrowthRate;
use App\Entity\Item;
use App\Entity\Machine;
use App\Entity\Move;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

//extract and transform machine information into entities from pokeapi
class MachineApi
{
    private PokeAPIGraphQLClient $client;
    private EntityManagerInterface $entityManager;

    public function __construct(PokeAPIGraphQLClient $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
    }

    public function getMachines(): array
    {
        $query = <<<GRAPHQL
query MyQuery {
  pokemon_v2_machine {
    pokemon_v2_move {
      name
    }
    machine_number
    pokemon_v2_item {
      name
    }
    pokemon_v2_versiongroup {
      name
    }
    pokemon_v2_growthrate {
      name
    }
  }
}

GRAPHQL;


        $content = $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);

        $machines = [];
        foreach ($content['data']['pokemon_v2_machine'] as $machine) {
            $machineEntity = new Machine();
            /** @var Move $move */
            $move = $this->entityManager->getRepository(Move::class)->findOneBy(
                [
                    'name' => $machine['pokemon_v2_move']['name']
                ]
            );
            $item = $this->entityManager->getRepository(Item::class)->findOneBy(
                [
                    'name' => $machine['pokemon_v2_item']['name']
                ]
            );
            $versionGroup =  $this->entityManager->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => $machine['pokemon_v2_versiongroup']['name']
                ]
            );
            if($machine['pokemon_v2_growthrate']) {
                $growthRate = $this->entityManager->getRepository(GrowthRate::class)->findOneBy(
                    [
                        'name' => $machine['pokemon_v2_growthrate']['name']
                    ]
                );
                $machineEntity->setGrowthRate($growthRate);

            }
            $machineEntity->setVersionGroup($versionGroup);
            $machineEntity->setMove($move);
            $machineEntity->setItem($item);
            $machineEntity->setMachineNumber($machine['machine_number']);
            $machines[] = $machineEntity;
        }

        return $machines;
    }
}
