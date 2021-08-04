<?php

namespace App\DataFixtures;

use App\Api\PokeAPI\PokemonEggGroupApi;
use App\Api\PokeAPI\ItemPocketApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadItemPocket extends Fixture implements AppFixtureInterface
{
    private ItemPocketApi $itemPocketApi;

    public function __construct(ItemPocketApi $itemPocketApi)
    {
        $this->itemPocketApi = $itemPocketApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->itemPocketApi->getItemPockets() as $itemPocket) {
            $manager->persist($itemPocket);
        }
        $manager->flush();
    }
}
