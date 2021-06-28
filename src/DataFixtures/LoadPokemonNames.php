<?php


namespace App\DataFixtures;


use App\Entity\Pokemon;
use App\Entity\PokemonName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadPokemonNames extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Pokemon[] $pokemons */
        $pokemons = $manager->getRepository(Pokemon::class)->findAllByIdentifier();

        $header = true;
        if (($fp = fopen("src/DataFixtures/data/pokemon_species_names.csv", "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ",")) !== false) {
                if ($header) {
                    $header = false;
                    continue;
                }

                $pokemonName = new PokemonName();
                $pokemonName->setLanguageId($row[1]);
                $pokemonName->setName($row[2]);
                $pokemons[$row[0]]->addName($pokemonName);

                $manager->persist($pokemonName);
            }
            fclose($fp);
            $manager->flush();
        }

        return true;
    }

    public function getDependencies()
    {
        return [LoadPokemon::class];
    }
}