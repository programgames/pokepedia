<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ContestEffectApi;
use App\Api\PokeAPI\PokemonSpeciesApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonSpecyEvolution extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokemonSpeciesApi $api;

    public function __construct(PokemonSpeciesApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getEvolutionInfos() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemonSpecies::class];
    }
}