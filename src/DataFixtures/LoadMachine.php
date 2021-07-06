<?php


namespace App\DataFixtures;


use App\Api\PokeAPI\GenerationApi;
use App\Api\PokeAPI\MachineApi;
use App\Api\PokeAPI\PokemonMoveApi;
use App\Api\PokeAPI\MoveApi;
use App\Api\PokeAPI\MoveNameApi;
use App\Api\PokeAPI\VersionGroupApi;
use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadMachine extends Fixture implements DependentFixtureInterface
{
    private MachineApi $machineApi;

    public function __construct(MachineApi $machineApi)
    {
        $this->machineApi = $machineApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->machineApi->getMachines() as $machine) {
            $manager->persist($machine);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadMove::class,LoadItem::class,LoadVersionGroup::class];
    }
}