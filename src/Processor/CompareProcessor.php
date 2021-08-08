<?php

namespace App\Processor;

use App\Api\Pokepedia\PokepediaMoveApi;
use App\Comparator\LevelMoveComparator;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Formatter\PokeApi\MoveFormatter;
use App\Generator\PokepediaMoveGenerator;
use App\Helper\GenerationHelper;
use App\Helper\MoveSetHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Process;

class CompareProcessor
{
    private EntityManagerInterface $em;
    private MoveSetHelper $moveSetHelper;
    private MoveFormatter $pokeApiFormatter;
    private LevelMoveComparator $levelMoveComparator;
    private ParameterBagInterface $parameterBag;
    private PokepediaMoveApi $api;
    private PokepediaMoveGenerator $generator;
    private GenerationHelper $helper;
    private PdoAdapter $cache;

    public function __construct(
        EntityManagerInterface $em,
        PokepediaMoveApi $api,
        MoveSetHelper $moveSetHelper,
        MoveFormatter $pokeApiFormatter,
        LevelMoveComparator $levelMoveComparator,
        PokepediaMoveGenerator $generator,
        GenerationHelper $helper,
        Connection $connection,
        ParameterBagInterface $parameterBag
    )
    {
        $this->em = $em;
        $this->api = $api;
        $this->moveSetHelper = $moveSetHelper;
        $this->levelMoveComparator = $levelMoveComparator;
        $this->generator = $generator;
        $this->helper = $helper;
        $this->cache = new PdoAdapter($connection);
        $this->parameterBag = $parameterBag;
        $this->pokeApiFormatter = $pokeApiFormatter;
    }

    /**
     * @param int $genId
     * @param int $learnMethodId
     * @param int $pokemonId
     * @param bool $retryMode
     * @return array
     * @throws InvalidArgumentException
     */
    public function compare(int $genId, int $learnMethodId, int $pokemonId, bool $retryMode = true): array
    {
        $generation = $this->em->getRepository(Generation::class)->find($genId);
        $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->find($learnMethodId);
        $pokemon = $this->em->getRepository(Pokemon::class)->find($pokemonId);

        $gen = $generation->getGenerationIdentifier();
        if (!$this->helper->hasPokemonMoveAvailabilitiesInGeneration($pokemon, $generation)) {
            return [
                'available' => false,
                'text' => sprintf(
                    '%s est indisponible pour la gen %s',
                    $this->moveSetHelper->getPokepediaPokemonName($pokemon),
                    $gen,
                )
            ];
        }

        $pokepediaData = $this->api->getLevelMoves(
            $this->moveSetHelper->getPokepediaPokemonName($pokemon),
            $gen
        );
        $pokepediaMoves = $pokepediaData['satanized']['moves'];
        $commentaries = $pokepediaData['satanized']['comments'];

        $pokeApiMoves = $this->pokeApiFormatter->getFormattedLevelPokeApiMoves(
            $pokemon,
            $gen,
            $learnmethod
        );

        $compareResult = $this->levelMoveComparator->levelMoveComparator($pokepediaMoves, $pokeApiMoves);
        if (!$compareResult['sameMoves']) {
            if ($retryMode) {
                $this->cache->delete(
                    sprintf('pokepedia.wikitext.pokemonmove.%s,%s.%s', $this->moveSetHelper->getPokepediaPokemonName($pokemon), $gen, MoveSetHelper::LEVELING_UP_TYPE)
                );
                $pokepediaData = $this->api->getLevelMoves(
                    $this->moveSetHelper->getPokepediaPokemonName($pokemon),
                    $gen
                );
                $pokepediaMoves = $pokepediaData['satanized']['moves'];
                $commentaries = $pokepediaData['satanized']['comments'];
                $compareResult = $this->levelMoveComparator->levelMoveComparator($pokepediaMoves, $pokeApiMoves);

                if (!$compareResult['sameMoves']) {
                    return $this->handleError(
                        $learnmethod,
                        $pokemon,
                        $gen,
                        $pokeApiMoves,
                        $commentaries,
                        $pokepediaData['section'],
                        $pokepediaData['page'],
                        $compareResult['differentOrder']
                    );
                }
            }
            return $this->handleError(
                $learnmethod,
                $pokemon, $gen,
                $pokeApiMoves,
                $commentaries,
                $pokepediaData['section'],
                $pokepediaData['page'],
                $compareResult['differentOrder']
            );
        }

        return [
            'available' => true,
            'diff' => false,
            'text' => sprintf(
                'Pas de diff pour la gen %s pour %s',
                $gen,
                $this->moveSetHelper->getPokepediaPokemonName($pokemon),
            )
        ];
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
        string $page,
        bool $differentOrder
    ): array
    {
        $generated = $this->generator->generateMoveWikiText($learnmethod, $pokemon, $gen, $pokeApiMoves, $commentaries, PokepediaMoveGenerator::CLI_MODE);
        $html = $this->generator->generateMoveWikiText($learnmethod, $pokemon, $gen, $pokeApiMoves, $commentaries, PokepediaMoveGenerator::HTML_MODE);
        $raw = $this->api->getRawWikitext(
            $this->moveSetHelper->getPokepediaPokemonName($pokemon),
            $gen,
        );

        $generatedPath = $this->parameterBag->get('kernel.project_dir') .
            DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'compare' .
            DIRECTORY_SEPARATOR . 'generated.txt';
        $rawPath = $this->parameterBag->get('kernel.project_dir') .
            DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'compare' .
            DIRECTORY_SEPARATOR . 'raw.txt';

        file_put_contents($generatedPath, $generated);
        file_put_contents($rawPath, $raw);

        $process = new Process(['git', 'diff', '--no-index', '-U1000', $rawPath, $generatedPath]);
        $process->run();
        $output = $process->getOutput();

        return [
            'section' => $section,
            'page' => $page,
            'available' => true,
            'diff' => true,
            'diffString' => $output,
            'generated' => $html,
            'wikitext' => $generated,
            'differentOrder' => $differentOrder,
            'text' => sprintf(
                'diff pour la gen %s pour %s',
                $gen,
                $this->moveSetHelper->getPokepediaPokemonName($pokemon),
            )
        ];
    }
}
