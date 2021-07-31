<?php


namespace App\Command\Installation;

use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Entity\PokemonName;
use App\Entity\VersionGroup;
use App\Helper\MoveSetHelper;
use App\MoveMapper;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportLGPEMoves extends Command
{
    protected static $defaultName = 'app:import:lgpe';
    protected static $defaultDescription = 'import bulbapedia lgpe movesets';

    private EntityManagerInterface $em;
    private BulbapediaMovesAPI $api;

    public function __construct(EntityManagerInterface $em, BulbapediaMovesAPI $api)
    {
        parent::__construct();

        $this->em = $em;
        $this->api = $api;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $moveMapper = new MoveMapper();
        $io = new SymfonyStyle($input, $output);

        $io->info("Importing Bulbapedia LGPE moves");

        $lgpe = $this->em->getRepository(VersionGroup::class)->findOneBy(['name' => 'lets-go']);

        $pokemonAvailabilities = $this->em->getRepository(PokemonAvailability::class)->findBy(['versionGroup' => $lgpe]);

        $levelup = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);
        $machine = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'machine']);

        $generation = $this->em->getRepository(Generation::class)->findOneBy(
            [
                'generationIdentifier' => 7
            ]
        );

        foreach ($pokemonAvailabilities as $pokemonAvailability) {
            $pokemon = $pokemonAvailability->getPokemon();
            if ($pokemon->isAlola()) {
                continue;
            }
            $io->info(sprintf('import levelup moves for LGPE %s', $pokemon->getName()));
            $moves = $this->api->getLevelMoves($pokemon, 7, true);
            if (array_key_exists('noform', $moves)) {
                foreach ($moves['noform'] as $move) {
                    if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                        $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $levelup);
                    } else {
                        throw new RuntimeException('Format roman');
                    }
                }
                $this->em->flush();
            } else {
                foreach ($moves as $form => $formMoves) {
                    $pokemon = $this->findPokemon($pokemon, $form);
                    foreach ($formMoves as $move) {
                        $pokemon = $this->findPokemon($pokemon, $form);
                        if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                            $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $levelup);
                        } else {
                            throw new RuntimeException('Format roman');
                        }
                    }
                }
                $this->em->flush();
            }
        }

        foreach ($pokemonAvailabilities as $pokemonAvailability) {
            $pokemon = $pokemonAvailability->getPokemon();
            if ($pokemon->isAlola() || $pokemon->getName() === 'mew') {
                continue;
            }
            $io->info(sprintf('import machine moves for LGPE %s', $pokemon->getName()));
            $moves = $this->api->getMachineMoves($pokemon, 7, true);
            if (array_key_exists('noform', $moves)) {
                foreach ($moves['noform'] as $move) {
                    if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                        $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $machine);
                    } else {
                        throw new RuntimeException('Format roman');
                    }
                }
                $this->em->flush();
            } else {
                foreach ($moves as $form => $formMoves) {
                    $pokemon = $this->findPokemon($pokemon, $form);
                    foreach ($formMoves as $move) {
                        if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                            $moveMapper->mapMoves($pokemon, $move, $generation, $this->em, $machine);
                        } else {
                            throw new RuntimeException('Format roman');
                        }
                    }
                }
                $this->em->flush();
            }
        }

        $io->info("Bulbapedia LGPE moves imported");

        return Command::SUCCESS;
    }

    private function findPokemon(Pokemon $pokemonEntity, string $name): Pokemon
    {
        /** @var PokemonName $pokemonName */
        $pokemonName = $this->em->getRepository(PokemonName::class)
            ->findPokemonByBulbapediaName($name);

        if (!$pokemonName) {
            throw new RuntimeException(sprintf('Pokemon with name %s not found', $name));
        }

        if ($pokemonName->getPokemon()->getIsDefault()) {
            return $pokemonName->getPokemon();
        }

        return $pokemonName->getPokemon();
    }
}
