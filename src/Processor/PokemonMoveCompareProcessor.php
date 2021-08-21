<?php

namespace App\Processor;

use App\Api\Pokepedia\PokepediaMoveApi;
use App\Comparator\LevelMoveComparator;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Exception\NotImplementedException;
use App\Formatter\Doctrine\MoveFormatter;
use App\Generator\PokepediaMoveGenerator;
use App\Helper\GenerationHelper;
use App\Helper\MoveSetHelper;
use App\Helper\PokemonHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\PdoAdapter;

class PokemonMoveCompareProcessor
{
    private EntityManagerInterface $em;
    private MoveSetHelper $moveSetHelper;
    private PokemonHelper $pokemonHelper;
    private MoveFormatter $pokeApiFormatter;
    private LevelMoveComparator $levelMoveComparator;
    private PokepediaMoveApi $api;
    private PokepediaMoveGenerator $generator;
    private GenerationHelper $helper;
    private PdoAdapter $cache;

    public function __construct(
        EntityManagerInterface $em,
        PokepediaMoveApi $api,
        MoveSetHelper $moveSetHelper,
        PokemonHelper $pokemonHelper,
        MoveFormatter $pokeApiFormatter,
        LevelMoveComparator $levelMoveComparator,
        PokepediaMoveGenerator $generator,
        GenerationHelper $helper,
        Connection $connection
    )
    {
        $this->em = $em;
        $this->api = $api;
        $this->moveSetHelper = $moveSetHelper;
        $this->pokemonHelper = $pokemonHelper;
        $this->levelMoveComparator = $levelMoveComparator;
        $this->generator = $generator;
        $this->helper = $helper;
        $this->cache = new PdoAdapter($connection);
        $this->pokeApiFormatter = $pokeApiFormatter;
    }

    public function process(Generation $generation, MoveLearnMethod $learnMethod, Pokemon $pokemon, bool $retryMode = true)
    {
        $gen = $generation->getGenerationIdentifier();
        if (!$this->helper->hasPokemonMoveAvailabilitiesInGeneration($pokemon, $generation)) {
            return;
        }

        $pokepediaData = $this->getPokepediaMovesByLearnmethod($learnMethod, $pokemon, $gen);
        $pokepediaMoves = $pokepediaData['satanized'];

        $pokeApiMoves = $this->pokeApiFormatter->getFormattedLevelPokeApiMoves(
            $pokemon,
            $gen,
            $learnMethod
        );

        if (!$this->levelMoveComparator->levelMoveComparator($pokepediaMoves, $pokeApiMoves)) {
            if ($retryMode) {
                $this->cache->delete(
                    sprintf('pokepedia.wikitext.pokemonmove.%s,%s.%s', $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon), $gen, MoveSetHelper::LEVELING_UP_TYPE)
                );
                $pokepediaData = $this->api->getLevelMoves(
                    $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon),
                    $gen
                );
                $pokepediaMoves = $pokepediaData['satanized']['moves'];
                $commentaries = $pokepediaData['satanized']['comments'];

                if (!$this->levelMoveComparator->levelMoveComparator($pokepediaMoves, $pokeApiMoves)) {
                    return $this->handleError(
                        $learnMethod,
                        $pokemon,
                        $gen,
                        $pokeApiMoves,
                        $commentaries,
                        $pokepediaData['section'],
                        $pokepediaData['page'],
                    );
                }
            }
            return $this->handleError(
                $learnMethod,
                $pokemon,
                $gen,
                $pokeApiMoves,
                $commentaries,
                $pokepediaData['section'],
                $pokepediaData['page'],
            );
        }
    }

    /**
     * @param MoveLearnMethod $learnmethod
     * @param $pokemon
     * @param $gen
     * @param array $pokeApiMoves
     * @param array $commentaries
     * @param string $section
     * @param string $page
     * @return array
     * @throws InvalidArgumentException
     */
    private function handleError(
        MoveLearnMethod $learnmethod,
        $pokemon,
        $gen,
        array $pokeApiMoves,
        array $commentaries,
        string $section,
        string $page
    ): array
    {
        $generated = $this->generator->generateMoveWikiText($learnmethod, $pokemon, $gen, $pokeApiMoves, $commentaries, PokepediaMoveGenerator::CLI_MODE);
        $raw = $this->api->getRawWikitext(
            $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon),
            $gen,
        );


    }

    private function getPokepediaMovesByLearnmethod(MoveLearnMethod $learnmethod, Pokemon $pokemon, int $gen)
    {
        $methodName = $learnmethod->getName();

        switch ($methodName) {
            case MoveSetHelper::LEVELING_UP_TYPE:
                return $this->api->getLevelMoves(
                    $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon),
                    $gen
                );
            case MoveSetHelper::EGG_TYPE:
                return $this->api->getEggMoves(
                    $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon),
                    $gen
                );
            case MoveSetHelper::TUTOR_TYPE:
                return $this->api->getTutorMoves(
                    $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon),
                    $gen
                );
            case MoveSetHelper::MACHINE_TYPE:
                return $this->api->getMachineMoves(
                    $this->pokemonHelper->getPokepediaPokemonUrlName($pokemon),
                    $gen
                );
        }

        throw new NotImplementedException(sprintf('handling %s moves is not implemented yet for pokepedia', $methodName));
    }
}
