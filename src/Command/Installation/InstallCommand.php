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

        $fixtureCommand = $this->getApplication()->find('app:fixtures:load');
        $lgpeMovesCommand = $this->getApplication()->find('app:import:lgpe');
        $pokemonMoveGen8 = $this->getApplication()->find('app:import:gen8');
        $pokeApiMoveCommand = $this->getApplication()->find('app:import:pokeapi:pokemonmoves');

        $mapperCommand->run(new ArrayInput([]), $output);
        $fixtureCommand->run(new ArrayInput([]), $output);
        $pokeApiMoveCommand->run(new ArrayInput([]), $output);
        $lgpeMovesCommand->run(new ArrayInput([]), $output);
        $pokemonMoveGen8->run(new ArrayInput([]), $output);

        $io->info("Application installed ! ");

        return Command::SUCCESS;
    }
}
