<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\MoveTargetApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadMoveTarget extends Fixture implements AppFixtureInterface
{
    private MoveTargetApi $api;

    public function __construct(MoveTargetApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getMoveTargets() as $target) {
            $manager->persist($target);
        }
        $manager->flush();
    }
}
