<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\ItemApi;
use App\Api\PokeAPI\PokedexApi;
use App\Api\PokeAPI\TypeNameApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokedex extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokedexApi $api;

    public function __construct(PokedexApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->api->getPokedexs() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadRegion::class];
    }
}
