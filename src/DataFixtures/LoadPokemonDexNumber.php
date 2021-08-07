<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ContestEffectApi;
use App\Api\PokeAPI\PokemonDexNumberApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonDexNumber extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokemonDexNumberApi $api;

    public function __construct(PokemonDexNumberApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokemonDexNumbersApi() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokedex::class,LoadPokemonSpecies::class];
    }
}