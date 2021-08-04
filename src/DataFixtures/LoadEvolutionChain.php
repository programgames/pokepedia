<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\EvolutionChainApi;
use App\Api\PokeAPI\PokemonEggGroupApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadEvolutionChain extends Fixture implements DependentFixtureInterface, AppFixtureInterface
{
    private EvolutionChainApi $api;

    public function __construct(EvolutionChainApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->api->getEvolutionChains() as $evolutionChain) {
            $manager->persist($evolutionChain);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemonSpecies::class,LoadItemCategory::class];
    }
}
