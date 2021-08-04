<?php

namespace App\DataFixtures;

use App\Entity\Pokemon;
use App\Entity\PokemonName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonNames extends Fixture implements DependentFixtureInterface,AppFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $file = __DIR__ . '/data/pokemon_names.csv';
        $row = 1;
        $repository = $manager->getRepository(Pokemon::class);
        if (($handle = fopen($file, 'rb')) !== false) {
            while (($data = fgetcsv($handle, 1000, ";")) !== false) {
                if ($row === 1) {
                    $row++;
                    continue;
                }

                $pokemon = $repository->findOneBy(
                    [
                        'name' => $data[0],
                    ]
                );
                $pokemonName = new PokemonName();
                $pokemonName->setPokemon($pokemon);
                $pokemonName->setPokepediaName($data[1]);
                $pokemonName->setBulbapediaName($data[2]);
                $manager->persist($pokemonName);
            }
            fclose($handle);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [LoadPokemon::class];
    }
}
