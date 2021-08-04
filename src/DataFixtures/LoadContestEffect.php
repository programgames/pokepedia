<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ContestEffectApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadContestEffect extends Fixture implements AppFixtureInterface
{
    private ContestEffectApi $api;

    public function __construct(ContestEffectApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getContestEffects() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}