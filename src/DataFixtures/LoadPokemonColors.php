<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\MoveDamageClassApi;
use App\Api\PokeAPI\PokemonColorApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonColors extends Fixture implements AppFixtureInterface
{
    private PokemonColorApi $api;

    public function __construct(PokemonColorApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokemonColors() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }
}