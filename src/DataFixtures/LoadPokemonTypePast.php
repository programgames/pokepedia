<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\MoveDamageClassApi;
use App\Api\PokeAPI\PokemonTypeApi;
use App\Api\PokeAPI\PokemonTypePastApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonTypePast extends Fixture implements AppFixtureInterface, DependentFixtureInterface
{
    private PokemonTypePastApi $pokemonTypePastApi;

    public function __construct(PokemonTypePastApi $pokemonTypePastApi)
    {
        $this->pokemonTypePastApi = $pokemonTypePastApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->pokemonTypePastApi->getPokemonTypePast() as $pokemonTypePast) {
            $manager->persist($pokemonTypePast);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [LoadPokemon::class,LoadType::class,LoadGeneration::class];
    }
}