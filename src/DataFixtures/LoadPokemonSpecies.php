<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\PokemonSpeciesApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonSpecies extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    private PokemonSpeciesApi $pokemonSpeciesApi;

    public function __construct(PokemonSpeciesApi $pokemonSpeciesApi)
    {
        $this->pokemonSpeciesApi = $pokemonSpeciesApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->pokemonSpeciesApi->getPokemonSpecies() as $pokemonSpecies) {
            $manager->persist($pokemonSpecies);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadGeneration::class,LoadGrowthRates::class,LoadPokemonHabitat::class,LoadPokemonColors::class,LoadPokemonShape::class];
    }
}
