<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ContestEffectApi;
use App\Api\PokeAPI\PokemonFormNameApi;
use App\Entity\PokemonFormName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonFormName extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private PokemonFormNameApi $api;

    public function __construct(PokemonFormNameApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getPokemonFormNames() as $entity) {
            $manager->persist($entity);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return[LoadPokemonForm::class];
    }
}