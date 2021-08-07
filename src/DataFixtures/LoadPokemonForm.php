<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\ItemApi;
use App\Api\PokeAPI\ItemCategoryApi;
use App\Api\PokeAPI\PokemonFormApi;
use App\Entity\PokemonForm;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonForm extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokemonFormApi $api;

    public function __construct(PokemonFormApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->api->getPokemonForms() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemon::class,LoadVersionGroup::class];
    }
}
