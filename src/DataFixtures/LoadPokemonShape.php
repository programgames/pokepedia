<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ContestEffectApi;
use App\Api\PokeAPI\PokemonShapeApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonShape extends Fixture implements AppFixtureInterface
{
    private PokemonShapeApi $api;

    public function __construct(PokemonShapeApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokemonShapes() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}