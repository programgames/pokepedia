<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\GrowthRateApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadGrowthRates extends Fixture implements AppFixtureInterface
{
    private GrowthRateApi $api;

    public function __construct(GrowthRateApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getGrowthRates() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}