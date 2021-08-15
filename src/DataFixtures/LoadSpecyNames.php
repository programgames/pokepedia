<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\PokemonSpecyNameApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadSpecyNames extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    private PokemonSpecyNameApi $pokemonSpecyNameApi;

    public function __construct(PokemonSpecyNameApi $pokemonSpecyNameApi)
    {
        $this->pokemonSpecyNameApi = $pokemonSpecyNameApi;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->pokemonSpecyNameApi->getFrenchSpecyNames() as $specyName) {
            $manager->persist($specyName);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemonSpecies::class];
    }
}
