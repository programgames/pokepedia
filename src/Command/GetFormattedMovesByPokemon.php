<?php


namespace App\Command;


use App\Api\PokeAPI\PokemonMoveApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetFormattedMovesByPokemon extends Command
{
    protected static $defaultName = 'app:format:pokeapi:moveset';
    protected static $defaultDescription = 'format movesets';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

//        $this->em->getMovesByPokemon(1);

        return Command::SUCCESS;
    }
}