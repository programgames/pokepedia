<?php


namespace App\Command\Installation;

use App\Api\PokeAPI\PokemonMoveApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportPokeapiPokemonMoves extends Command
{
    protected static $defaultName = 'app:import:pokeapi:pokemonmoves';
    protected static $defaultDescription = 'Import pokeapi movesets';

    private PokemonMoveApi $api;
    private EntityManagerInterface $em;

    /**
     * ImportMovesByPokemon constructor.
     * @param PokemonMoveApi $api
     * @param EntityManagerInterface $em
     */
    public function __construct(PokemonMoveApi $api, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->api = $api;
        $this->em = $em;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info("Importing Pokeapi pokemon moves (1~2 hours");
        $flush = 1000;
        foreach ($this->api->getMovesByPokemon() as $pokemonMove) {
            $this->em->persist($pokemonMove);
            $flush--;
            if ($flush === 0) {
                $this->em->flush();
                $flush = 1000;
            }
        }
        $this->em->flush();

        $io->info("Pokeapi moves imported");

        return Command::SUCCESS;
    }
}
