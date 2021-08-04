<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\MoveApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadMove extends Fixture implements AppFixtureInterface
{
    private MoveApi $moveApi;

    public function __construct(MoveApi $moveApi)
    {
        $this->moveApi = $moveApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->moveApi->getMoves() as $moves) {
            $manager->persist($moves);
        }
        $manager->flush();
    }
}
