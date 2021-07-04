<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\EggGroupApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadEggGroup  extends Fixture
{
    private EggGroupApi $eggGroupApi;

    public function __construct(EggGroupApi $eggGroupApi)
    {
        $this->eggGroupApi = $eggGroupApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->eggGroupApi->getPokemons() as $eggGroup) {
            $manager->persist($eggGroup);
        }
        $manager->flush();
    }
}