<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\MachineApi;
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
        return [LoadLetsGoMachine::class,LoadItem::class,LoadVersionGroup::class];
    }
}
