<?php

declare(strict_types=1);

namespace App\Command;

use App\Api\Pokepedia\Client\PokepediaMoveApiClient;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\PokemonMoveAvailability;
use App\Helper\GenerationHelper;
use App\Helper\MoveSetHelper;
use App\Helper\PokemonHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DownloadPokepediaPokemonMoves extends Command
{
    private EntityManagerInterface $em;
    private GenerationHelper $helper;
    private MoveSetHelper $moveSetHelper;
    private PokemonHelper $pokemonHelper;
    private AbstractAdapter $cache;
    private PokepediaMoveApiClient $moveClient;


    protected static $defaultName = 'app:download:pokepedia:pokemon:moves';

    /**
     * DownloadPokepediaPokemonMoves constructor.
     * @param EntityManagerInterface $em
     * @param GenerationHelper $helper
     * @param MoveSetHelper $moveSetHelper
     * @param PokepediaMoveApiClient $moveClient
     */
    public function __construct(Connection $connection, EntityManagerInterface $em,PokemonHelper $pokemonHelper,GenerationHelper $helper, MoveSetHelper $moveSetHelper, PokepediaMoveApiClient $moveClient)
    {
        parent::__construct();
        $this->cache = new PdoAdapter($connection);
        $this->em = $em;
        $this->helper = $helper;
        $this->moveSetHelper = $moveSetHelper;
        $this->pokemonHelper = $pokemonHelper;
        $this->moveClient = $moveClient;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pokemons = $this->em->getRepository(PokemonMoveAvailability::class)->findPokemonWithSpecificPageStartingAt(1);
        $generations = $this->em->getRepository(Generation::class)->findAll();

        foreach ($generations as $generation) {
            if($generation->getGenerationIdentifier() <= 6) {
                continue;
            }
            foreach ($pokemons as $pokemon) {
                if (!$this->helper->hasPokemonMoveAvailabilitiesInGeneration($pokemon, $generation)) {
                    continue;
                }

                $pokemonName = $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon);
                $this->cache->get(
                    sprintf('pokepedia.wikitext.pokemonmove.%s,%s.%s', str_replace(':','-',$pokemonName), $generation->getGenerationIdentifier(), MoveSetHelper::LEVELING_UP_TYPE),
                    function () use ($pokemonName, $generation) {
                        return $this->moveClient->getPokemonMoves(
                            $pokemonName,
                            $generation->getGenerationIdentifier(),
                            MoveSetHelper::LEVELING_UP_TYPE
                        );
                    }
                );
            }
        }

        return Command::SUCCESS;
    }
}
