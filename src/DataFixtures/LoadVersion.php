<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\ItemApi;
use App\Api\PokeAPI\PokedexApi;
use App\Api\PokeAPI\TypeNameApi;
use App\Api\PokeAPI\VersionApi;
use App\Entity\Version;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadVersion extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private VersionApi $api;

    public function __construct(VersionApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->api->getVersions() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
       return [LoadVersionGroup::class];
    }
}
