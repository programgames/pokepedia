<?php

namespace App\DataFixtures;

use App\Entity\MoveName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadFrenchMovesAliases extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $this->replaceApostrophe($manager);
        $file = __DIR__ . '/data/french_move_alias.csv';
        $row = 1;
        $repository = $manager->getRepository(MoveName::class);
        if (($handle = fopen($file, 'rb')) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if ($row === 1) {
                    $row++;
                    continue;
                }
                preg_match('/\d+/', $data[2], $matches);
                $gen1 = $matches[0];
                preg_match('/\d$/', $data[2], $matches);
                $gen2 = $matches[0];

                $name = $repository->findOneBy(
                    [
                        'name' => $data[0],
                        'language' => 5
                    ]
                );
                for ($i = $gen1; $i <= $gen2; $i++) {
                    $func = sprintf('%s%s', 'setGen', $i);

                    $name->$func($data[1]);
                }
                $manager->persist($name);
            }
            fclose($handle);
        }

        $manager->flush();
    }

    private function replaceApostrophe(ObjectManager $manager): void
    {
        $moves = $manager->getRepository(MoveName::class)->findBy(['language' => 5]);

        foreach ($moves as $move) {
            $move->setName(str_replace('’', '\'', $move->getName()));
            $manager->persist($move);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadMoveNames::class];
    }
}