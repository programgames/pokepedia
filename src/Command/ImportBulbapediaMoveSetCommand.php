<?php

namespace App\Command;

use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Move;
use App\Entity\Pokemon;
use App\Generation\GenerationHelper;
use App\MoveSet\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ImportBulbapediaMoveSetCommand extends Command
{
    protected static $defaultName = 'app:bmoveset';
    protected static $defaultDescription = 'Import bulbapedia mooveset';

    private HttpClientInterface $client;
    private EntityManagerInterface $entityManager;
    private BulbapediaMovesAPI $bulbapediaMovesAPI;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, BulbapediaMovesAPI $bulbapediaMovesAPI)
    {
        parent::__construct(self::$defaultName);

        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->bulbapediaMovesAPI = $bulbapediaMovesAPI;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $gen = 5;
        $pokemons = $this->entityManager->getRepository(Pokemon::class)->findBy(
            [
                'generation' => $gen,
            ]
        );

        foreach ($pokemons as $pokemon) {
            $io->info(strtr('Importing %pokemon%  , id  : %id%', [
                '%pokemon%' => $pokemon->getEnglishName(),
                '%id%' => $pokemon->getPokemonId()
            ]));
            $moveNames = $this->bulbapediaMovesAPI->getTutorMoves($pokemon, GenerationHelper::genNumberToLitteral($gen));
            foreach ($moveNames as $moveName) {
                $move = new Move();
                $move->addPokemon($pokemon);
                $move->setEnglishName($moveName[1]);
                $games = [];
                if (array_key_exists(9, $moveName) && $moveName[9] === 'yes') {
                    $games['B/W'] = true;
                }
                if (array_key_exists(10, $moveName) && $moveName[10] === 'yes') {
                    $games['B2/W2'] = true;
                }
                $move->setGames(json_encode($games));
                $move->setLearningType(MoveSetHelper::TUTORING);
                $move->setGeneration($gen);
                $this->entityManager->persist($move);
            }
            $this->entityManager->flush();
        }
        return Command::SUCCESS;
    }
}
