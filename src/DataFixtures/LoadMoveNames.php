<?php


namespace App\DataFixtures;


use App\Entity\MoveName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadMoveNames extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $header = true;
        if (($fp = fopen("src/DataFixtures/data/move_names.csv", "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ",")) !== false) {
                if ($header) {
                    $header = false;
                    continue;
                }

                $pokemon = new MoveName();
                $pokemon->setMoveIdentifier($row[0]);
                $pokemon->setLanguageId($row[1]);
                $pokemon->setName($row[2]);
                $manager->persist($pokemon);
            }
            fclose($fp);
            $manager->flush();
        }

        return true;
    }

    public function getDependencies()
    {
        return [LoadPokemon::class];
    }
}