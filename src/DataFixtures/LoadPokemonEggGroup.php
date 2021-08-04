<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\MoveDamageClassApi;
use App\Api\PokeAPI\PokemonEggGroupApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonEggGroup extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokemonEggGroupApi $api;

    public function __construct(PokemonEggGroupApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokemonEggGroups() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemon::class,LoadEggGroup::class];
    }
}