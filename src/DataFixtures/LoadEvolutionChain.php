<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\EvolutionChainApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadEvolutionChain extends Fixture implements DependentFixtureInterface, AppFixtureInterface
{
    private EvolutionChainApi $evolutionChainApi;

    public function __construct(EvolutionChainApi $evolutionChainApi)
    {
        $this->evolutionChainApi = $evolutionChainApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->evolutionChainApi->getEvolutionChains() as $evolutionChain) {
            $manager->persist($evolutionChain);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemonSpecies::class];
    }
}
