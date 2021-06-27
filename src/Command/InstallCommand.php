<?php

namespace App\Command;

use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected static $defaultName = 'app:install';
    protected static $defaultDescription = 'Add a short description for your command';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->loadPokemonNames($input, $output);
        $this->loadMoveNames($input, $output);
        $this->loadPokemons($input,$output);

        return Command::SUCCESS;
    }

    private function loadPokemonNames(InputInterface $input, OutputInterface $output)
    {
        $header = true;
        if (($fp = fopen("src/data/pokemon_species_names.csv", "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ",")) !== false) {
                if ($header) {
                    $header = false;
                    continue;
                }

                $pokemon = new PokemonName();
                $pokemon->setSpeciesId($row[0]);
                $pokemon->setLanguageId($row[1]);
                $pokemon->setName($row[2]);
                $this->entityManager->persist($pokemon);
            }
            fclose($fp);
            $this->entityManager->flush();
        }

        return true;
    }

    private function loadMoveNames(InputInterface $input, OutputInterface $output)
    {
        $header = true;
        if (($fp = fopen("src/data/move_names.csv", "r")) !== false) {
            while (($row = fgetcsv($fp, 1000, ",")) !== false) {
                if ($header) {
                    $header = false;
                    continue;
                }

                $pokemon = new MoveName();
                $pokemon->setMoveId($row[0]);
                $pokemon->setLanguageId($row[1]);
                $pokemon->setName($row[2]);
                $this->entityManager->persist($pokemon);
            }
            fclose($fp);
            $this->entityManager->flush();
        }

        return true;
    }

    private function loadPokemons(InputInterface $input, OutputInterface $output)
    {
        $pokemonNamesRepository = $this->entityManager->getRepository(PokemonName::class);
        $englishPokemonNames = $pokemonNamesRepository->findBy(
            [
                'languageId' => 9
            ]
        );

        foreach ($englishPokemonNames as $englishPokemonName) {

            $pokemondId = $englishPokemonName->getSpeciesId();
            if($pokemondId >= 1 && $pokemondId <= 151) {
                $generation = 1;
            } elseif ($pokemondId > 151 && $pokemondId <= 251) {
                $generation = 1;
            } elseif ($pokemondId > 252 && $pokemondId <= 386) {
                $generation = 3;
            } elseif ($pokemondId > 386 && $pokemondId <= 493) {
                $generation = 4;
            } elseif ($pokemondId > 493 && $pokemondId <= 649) {
                $generation = 5;
            } elseif ($pokemondId > 649 && $pokemondId <= 721) {
                $generation = 6;
            } elseif ($pokemondId > 721 && $pokemondId <= 809) {
                $generation = 7;
            } elseif ($pokemondId > 809 && $pokemondId <= 898) {
                $generation = 8;
            }  else {
                $generation = 0;
            }

            $pokemon = new Pokemon();
            $pokemon->setEnglishName($englishPokemonName->getName());
            $pokemon->setPokemonId($pokemondId);
            $pokemon->setGeneration($generation);
            $this->entityManager->persist($pokemon);
        }
        $this->entityManager->flush();
    }
}
