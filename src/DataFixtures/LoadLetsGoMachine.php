<?php

namespace App\DataFixtures;

use App\Api\Bulbapedia\BulbapediaMachineAPI;
use App\Entity\ItemName;
use App\Entity\Machine;
use App\Entity\MoveName;
use App\Entity\VersionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadLetsGoMachine extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    private BulbapediaMachineAPI $api;

    public function __construct(BulbapediaMachineAPI $api)
    {
        $this->api = $api;
    }

    public function load(ObjectManager $manager)
    {
        $generation = '72';

        $lgpe = $manager->getRepository(VersionGroup::class)->findOneBy(['name' => 'lets-go']);
        $itemNameRepository = $manager->getRepository(ItemName::class);
        $moveNameRepository = $manager->getRepository(MoveName::class);
        foreach ($this->getItemNames() as $itemName) {
            $moveName = $this->api->getMoveNameByItemAndGeneration($itemName, $generation);
            $moveNameEntity = $moveNameRepository->findEnglishMoveNameByName($moveName, 7);
            $itemNameEntity = $itemNameRepository->findOneBy(
                [
                    'name' => $itemName,
                    'language' => 9
                ]
            );
            $machine = new Machine();
            $machine->setVersionGroup($lgpe);
            $machine->setMove($moveNameEntity->getMove());
            $machine->setMachineNumber(0);
            $machine->setItem($itemNameEntity->getItem());
            $manager->persist($machine);
        }
        $manager->flush();
    }

    private function getItemNames(): array
    {
        return [
            "TM01", "TM02", "TM03", "TM04", "TM05", "TM06", "TM07", "TM08", "TM09", "TM10", "TM11", "TM12", "TM13",
            "TM14", "TM15", "TM16", "TM17", "TM18", "TM19", "TM20", "TM21", "TM22", "TM23", "TM24", "TM25", "TM26",
            "TM27", "TM28", "TM29", "TM30", "TM31", "TM32", "TM33", "TM34", "TM35", "TM36", "TM37", "TM38", "TM39",
            "TM40", "TM41", "TM42", "TM43", "TM44", "TM45", "TM46", "TM47", "TM48", "TM49", "TM50", "TM51", "TM52",
            "TM53", "TM54", "TM55", "TM56", "TM57", "TM58", "TM59", "TM60",
        ];
    }

    public function getDependencies()
    {
        return [
            LoadVersionGroup::class, LoadItemNames::class, LoadMoveNames::class
        ];
    }
}
