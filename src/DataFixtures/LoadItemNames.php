<?php


namespace App\DataFixtures;


use App\Api\PokeAPI\GenerationApi;
use App\Api\PokeAPI\ItemApi;
use App\Api\PokeAPI\ItemNameApi;
use App\Api\PokeAPI\PokemonMoveApi;
use App\Api\PokeAPI\MoveApi;
use App\Api\PokeAPI\PokemonSpecyNameApi;
use App\Api\PokeAPI\VersionGroupApi;
use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadItemNames extends Fixture
{
    private ItemNameApi $itemNameApi;

    public function __construct(ItemNameApi $itemNameApi)
    {
        $this->itemNameApi = $itemNameApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->itemNameApi->getItemNames() as $itemName) {
            $manager->persist($itemName);
        }
        $manager->flush();
    }
}