<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\MoveDamageClassApi;
use App\Api\PokeAPI\PokemonHabitatApi;
use App\Api\PokeAPI\PokemonTypeApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonHabitat extends Fixture implements AppFixtureInterface
{
    private PokemonHabitatApi $api;

    public function __construct(PokemonHabitatApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokemonHabitats() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}