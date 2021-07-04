<?php


namespace App\Command;


use App\Api\PokeAPI\MoveRequestApi;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportMovesByPokemon extends Command
{
    protected static $defaultName = 'app:import:pokeapi:moveset';
    protected static $defaultDescription = 'Import pokeapi movesets';

    private MoveRequestApi $api;

    public function __construct(MoveRequestApi $api)
    {
        parent::__construct();
        $this->api = $api;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $json = $this->api->getMovesByPokemon(1);

        return Command::SUCCESS;
    }
}