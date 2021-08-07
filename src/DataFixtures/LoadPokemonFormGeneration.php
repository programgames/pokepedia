<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\ItemApi;
use App\Api\PokeAPI\ItemCategoryApi;
use App\Api\PokeAPI\PokemonFormGenerationApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonFormGeneration extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokemonFormGenerationApi $api;

    public function __construct(PokemonFormGenerationApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->api->getPokemonFormCategories() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemonForm::class,LoadGeneration::class];
    }
}
