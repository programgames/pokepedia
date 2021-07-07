<?php


namespace App\Command;


use App\Api\PokeAPI\PokemonMoveApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportMovesByPokemon extends Command
{
    protected static $defaultName = 'app:import:pokeapi:moveset';
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

        $flush = 200;
        $entity = 0;
        foreach ( $this->api->getMovesByPokemon() as $pokemonMove) {
            $this->em->persist($pokemonMove);
            $flush--;
            $entity ++;
            if($flush === 0) {
                $io->info('flushing');
                $this->em->flush();
                $flush = 200;
            }
            $io->info(sprintf('Entity %s', $entity));
        }
        $this->em->flush();


        return Command::SUCCESS;
    }
}
