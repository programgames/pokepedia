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
            'green',
            'blue',
            'yellow',
            'silver',
            'gold',
            'crystal',
            'emerald',
            'ruby',
            'sapphire',
            'firered',
            'leafgreen',
            'diamond',
            'pearl',
            'platinum',
            'heartgold',
            'soulsilver',
            'black',
            'white',
            'black2',
            'white2',
            'x',
            'y',
            'omegaruby',
            'alpha_sapphire',
            'sun',
            'moon',
            'ultrasun',
            'ultramoon',
            'sword',
            'shield',
        ];

        foreach ($games as $gameName) {
            $game = new Game();
            $game->setName($gameName);

            $manager->persist($game);
        }

        $manager->flush();
    }
}