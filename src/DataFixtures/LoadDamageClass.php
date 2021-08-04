<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\MoveDamageClassApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadDamageClass extends Fixture implements AppFixtureInterface
{
    private MoveDamageClassApi $damageClassApi;

    public function __construct(MoveDamageClassApi $damageClassApi)
    {
        $this->damageClassApi = $damageClassApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->damageClassApi->getDamageClasses() as $damageClass) {
            $manager->persist($damageClass);
        }
        $manager->flush();
    }
}