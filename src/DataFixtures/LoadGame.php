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
            'DP' => ['gen'=> 4, 'isFirst'=> true],
            'PtHGSS ' => ['gen'=> 4, 'isFirst'=> false],
            'BW' => ['gen'=> 5, 'isFirst'=> true],
            'B2W2' => ['gen'=> 5, 'isFirst'=> false],
        ];

        foreach ($games as $gameName => $data) {
            $game = new Game();
            $game->setName($gameName);
            $game->setGen($data['gen']);

            $manager->persist($game);
        }

        $manager->flush();
    }
}
