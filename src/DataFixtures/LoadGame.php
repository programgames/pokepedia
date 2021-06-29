<?php


namespace App\DataFixtures;


use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadGame extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $games = [
            'green' => 1,
            'blue' => 1,
            'red' => 1,
            'yellow' => 1,
            'silver' => 2,
            'gold' => 2,
            'crystal' => 2,
            'emerald' => 3,
            'ruby' => 3,
            'sapphire' => 3,
            'firered' => 3,
            'leafgreen' => 3,
            'diamond' => 4,
            'pearl' => 4,
            'platinum' => 4,
            'heartgold' => 4,
            'soulsilver' => 4,
            'black' => 5,
            'white' => 5,
            'black2' => 5,
            'white2' => 5,
            'x' => 6,
            'y' => 6,
            'omegaruby' => 6,
            'alpha_sapphire' => 6,
            'sun' => 7,
            'moon' => 7,
            'ultrasun' => 7,
            'ultramoon' => 7,
            'sword' => 8,
            'shield' => 8,
        ];

        foreach ($games as $name => $gen ) {
            $gameEntity = new Game();
            $gameEntity->setGen($gen);
            $gameEntity->setName($name);

            $manager->persist($gameEntity);
        }

        $manager->flush();
    }
}