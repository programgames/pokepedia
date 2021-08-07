<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\MachineApi;
use App\Api\PokeAPI\PokemonGameIndexApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonGameIndexes extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    private PokemonGameIndexApi $api;

    public function __construct(PokemonGameIndexApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokemonGameIndexes() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemon::class,LoadVersion::class];
    }
}
