<?php


namespace App\DataFixtures;


use App\Api\PokeAPI\GenerationApi;
use App\Api\PokeAPI\VersionGroupApi;
use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadVersionGroup extends Fixture implements DependentFixtureInterface
{
    private VersionGroupApi $versionGroupApi;

    public function __construct(VersionGroupApi $versionGroupApi)
    {
        $this->versionGroupApi = $versionGroupApi;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->versionGroupApi->getVersionGroups() as $versionGroup) {
            $manager->persist($versionGroup);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadGeneration::class];
    }
}