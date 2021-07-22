<?php


namespace App\DataFixtures;


use App\Entity\Move;
use App\Entity\MoveName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadLetsGoMoves extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $moves = [
            "Écrous d'Poing",
            "Évo-Chardasso",
            "Évo-Congélo",
            "Évo-Dynamo",
            "Évo-Écolo",
            "Évo-Fabulo",
            "Évo-Flambo",
            "Évo-Psycho",
            "Évo-Ténébro",
            "Évo-Thalasso",
            "Pika-Fracas",
            "Pika-Piqué",
            "Pika-Splash",
            "Pika-Sprint",
        ];

        foreach ($moves as $move) {
            $moveEntity = new Move();
            $moveEntity->setName($move);
            $moveNameEntity = new MoveName();
            $moveNameEntity->setName($move);
            $moveNameEntity->setMove($moveEntity);
            $moveNameEntity->setLanguage(5);
            $manager->persist($moveEntity);
            $manager->persist($moveNameEntity);
        }
        $manager->flush();
    }
}