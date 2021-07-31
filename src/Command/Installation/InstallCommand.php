<?php

declare(strict_types=1);

namespace App\Command\Installation;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallCommand extends Command
{
    protected static $defaultName = 'app:install';
    protected static $defaultDescription = 'Install application';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $mapperCommand = $this->getApplication()->find('app:mapper');
        $fixtureCommand = $this->getApplication()->find('doctrine:fixtures:load');
        $availabilitiesCommand = $this->getApplication()->find('app:generate:availabilities');
        $pokepediaPokemontypeCommand = $this->getApplication()->find('app:pokepedia:types');
        $pokeApiMoveCommand = $this->getApplication()->find('app:import:pokeapi:pokemonmoves');

        $mapperCommand->run(new ArrayInput([]),$output);
        $fixtureCommand->run(new ArrayInput(['--append' => true ]),$output);
        $availabilitiesCommand->run(new ArrayInput([]),$output);
        $pokepediaPokemontypeCommand->run(new ArrayInput([]),$output);
        $pokeApiMoveCommand->run(new ArrayInput([]),$output);

        $io->info("Application installed ! ");

        return Command::SUCCESS;
    }
}
