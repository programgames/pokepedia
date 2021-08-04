<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\ItemNameApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadItemNames extends Fixture implements DependentFixtureInterface,AppFixtureInterface
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

    public function getDependencies()
    {
        return [LoadItem::class];
    }
}
