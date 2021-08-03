<?php

namespace App\DataFixtures;

use App\Entity\Move;
use App\Entity\MoveName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadExceptionData extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->loadViceGrip($manager);
        $this->loadBranchPoke($manager);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadMoveNames::class];
    }

    private function loadViceGrip(ObjectManager $manager): void
    {
        $move = $manager->getRepository(MoveName::class)->findOneBy(['name' => 'Vice Grip']);
        for ($i = 1; $i < 8; $i++) {
            $move->{'setGen' . $i}('Vise Grip');
        }
        $manager->persist($move);
    }

    private function loadBranchPoke(ObjectManager $manager): void
    {
        $move = new Move();
        $move->setName('branch-poke');

        $moveName1 = new MoveName();
        $moveName1->setName('Branch Poke');
        $moveName1->setMove($move);
        $moveName1->setLanguage(9);

        $moveName2 = new MoveName();
        $moveName2->setName('Tapotige');
        $moveName2->setMove($move);
        $moveName2->setLanguage(5);


        $manager->persist($move);
        $manager->persist($moveName1);
        $manager->persist($moveName2);
    }
}
