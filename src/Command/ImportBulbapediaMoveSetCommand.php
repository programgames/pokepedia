<?php

namespace App\Command;

use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;
use App\Manager\MoveSetManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportBulbapediaMoveSetCommand extends Command
{
    protected static $defaultName = 'app:import:bulbapedia:moveset';
    protected static $defaultDescription = 'Import bulbapedia movesets';

    private EntityManagerInterface $entityManager;
    private BulbapediaMovesAPI $bulbapediaMovesAPI;
    private MoveSetManager $moveSetManager;

    public function __construct(EntityManagerInterface $entityManager, BulbapediaMovesAPI $bulbapediaMovesAPI, MoveSetManager $moveSetManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->bulbapediaMovesAPI = $bulbapediaMovesAPI;
        $this->moveSetManager = $moveSetManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('generation', InputArgument::REQUIRED, 'Generation')
            ->addArgument('type', null, InputArgument::REQUIRED, 'Move type');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $gen = $input->getArgument('generation');
        $type = $input->getArgument('type');


        if($gen === 'all') {
            $pokemons = $this->entityManager->getRepository(Pokemon::class)->findAll();
        } else
        $pokemons = $this->entityManager->getRepository(Pokemon::class)->findBy(
            [
                'generation' => $gen,
//                'pokemonIdentifier' => 134
            ]
        );

        /** @var Pokemon $pokemon */
        foreach ($pokemons as $pokemon) {
            if (!in_array($type, ['all', MoveSetHelper::TUTORING_TYPE, MoveSetHelper::LEVELING_UP_TYPE])) {
                $io->error('Unknown moveset type');
                return Command::FAILURE;
            }

            if (in_array($type, ['all', MoveSetHelper::TUTORING_TYPE])) {
                $io->info(
                    strtr(
                        'Importing Pokemon %pokemon% moves for type %type%',
                        [
                            '%pokemon%' => $pokemon->getPokemonIdentifier(),
                            '%type%' => MoveSetHelper::TUTORING_TYPE
                        ]
                    )
                );
                $this->moveSetManager->importTutoringMoves($pokemon, $gen);
            }

            if (in_array($type, ['all', MoveSetHelper::LEVELING_UP_TYPE])) {
                $io->info(
                    strtr(
                        'Importing Pokemon %pokemon% moves for type %type%',
                        [
                            '%pokemon%' => $pokemon->getPokemonIdentifier(),
                            '%type%' => MoveSetHelper::LEVELING_UP_TYPE
                        ]
                    )
                );
                $this->moveSetManager->importLevelingMoves($pokemon, $gen);
            }
        }
        return Command::SUCCESS;
    }
}
