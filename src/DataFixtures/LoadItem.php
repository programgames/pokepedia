<?php


namespace App\DataFixtures;


use App\Api\PokeAPI\GenerationApi;
use App\Api\PokeAPI\ItemApi;
use App\Api\PokeAPI\ItemNameApi;
use App\Api\PokeAPI\MachineApi;
use App\Api\PokeAPI\MoveApi;
use App\Api\PokeAPI\MoveNameApi;
use App\Api\PokeAPI\VersionGroupApi;
use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadItem extends Fixture
{
    private ItemApi $itemApi;

    public function __construct(ItemApi $itemApi)
    {
        $this->itemApi = $itemApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->itemApi->getItems() as $item) {
            $manager->persist($item);
        }
        $manager->flush();
    }
}