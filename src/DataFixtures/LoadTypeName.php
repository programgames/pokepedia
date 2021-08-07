<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\TypeNameApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadTypeName extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private TypeNameApi $api;

    public function __construct(TypeNameApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getTypeNames() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadType::class];
    }
}
