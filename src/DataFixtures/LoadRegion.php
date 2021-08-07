<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\PokemonEggGroupApi;
use App\Api\PokeAPI\ItemPocketApi;
use App\Api\PokeAPI\RegionApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadRegion extends Fixture implements AppFixtureInterface
{
    private RegionApi $api;

    public function __construct(RegionApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getRegions() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}
