<?php


namespace App\DataFixtures;

use App\Api\PokeAPI\ItemFlingEffectApi;
use App\Api\PokeAPI\MoveDamageClassApi;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadItemFlingEffect extends Fixture implements AppFixtureInterface
{
    private ItemFlingEffectApi  $api;

    public function __construct(ItemFlingEffectApi $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->api->getFlyingEffects() as $effect) {
            $manager->persist($effect);
        }
        $manager->flush();
    }
}