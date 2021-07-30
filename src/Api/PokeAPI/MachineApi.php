<?php


namespace App\Api\PokeAPI;


use App\Api\PokeAPI\Client\PokeAPIGraphQLClient;
use App\Entity\Item;
use App\Entity\Machine;
use App\Entity\Move;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

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
  }
}

GRAPHQL;

        $cache = new FilesystemAdapter();

        $json = $cache->get(
            sprintf('pokeapi.%s', 'machine'),
            function (ItemInterface $item) use ($query) {
                return $this->client->sendRequest('https://beta.pokeapi.co/graphql/v1beta', $query);
            }
        );
        $machines = [];
        foreach ($json['data']['pokemon_v2_machine'] as $machine) {
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
            $machineEntity->setVersionGroup($versionGroup);
            $machineEntity->setMove($move);
            $machineEntity->setItem($item);
            $machineEntity->setMachineNumber($machine['machine_number']);
            $machines[] = $machineEntity;
        }

        return $machines;
    }
}
