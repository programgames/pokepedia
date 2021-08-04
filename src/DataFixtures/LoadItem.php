<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\ItemApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadItem extends Fixture implements AppFixtureInterface
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
