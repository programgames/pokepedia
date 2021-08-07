<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ContestEffectApi;
use App\Api\PokeAPI\PokedexVersionGroupApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokedexVersionGroup extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokedexVersionGroupApi $api;

    public function __construct(PokedexVersionGroupApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokedexVersionGroups() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadVersionGroup::class,LoadPokedex::class];
    }
}