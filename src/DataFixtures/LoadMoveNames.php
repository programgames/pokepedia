<?php


namespace App\DataFixtures;


use App\Api\PokeAPI\GenerationApi;
use App\Api\PokeAPI\MoveApi;
use App\Api\PokeAPI\PokemonSpecyNameApi;
use App\Api\PokeAPI\VersionGroupApi;
use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadMoveNames extends Fixture implements DependentFixtureInterface
{
    private PokemonSpecyNameApi $moveNameApi;

    public function __construct(PokemonSpecyNameApi $versionGroupApi)
    {
        $this->moveNameApi = $versionGroupApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->moveNameApi->getSpecyNames() as $moveName) {
            $manager->persist($moveName);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadMove::class];
    }
}