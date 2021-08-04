<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\MoveDamageClassApi;
use App\Api\PokeAPI\PokemonTypeApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonType extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokemonTypeApi $pokemonTypeApi;

    public function __construct(PokemonTypeApi $pokemonTypeApi)
    {
        $this->pokemonTypeApi = $pokemonTypeApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->pokemonTypeApi->getPokemonTypes() as $pokemonType) {
            $manager->persist($pokemonType);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadType::class];
    }
}