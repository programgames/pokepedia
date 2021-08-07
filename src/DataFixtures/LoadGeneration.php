<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\GenerationApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadGeneration extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private GenerationApi $generationApi;

    public function __construct(GenerationApi $generationApi)
    {
        $this->generationApi = $generationApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->generationApi->getGenerations() as $generation) {
            $manager->persist($generation);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadRegion::class];
    }
}
