<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ContestTypeApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadContestType extends Fixture implements AppFixtureInterface
{
    private ContestTypeApi $api;

    public function __construct(ContestTypeApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->api->getContestTypes() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}