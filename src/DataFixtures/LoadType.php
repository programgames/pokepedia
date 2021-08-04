<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\MoveDamageClassApi;
use App\Api\PokeAPI\PokemonTypeApi;
use App\Api\PokeAPI\TypeApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadType extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{
    private TypeApi $typeApi;

    public function __construct(TypeApi $typeApi)
    {
        $this->typeApi = $typeApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->typeApi->getTypes() as $type) {
            $manager->persist($type);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [LoadGeneration::class,LoadItemFlingEffect::class];
    }
}