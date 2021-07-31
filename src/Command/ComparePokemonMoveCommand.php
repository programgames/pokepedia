<?php


namespace App\Command;


use App\Api\Pokepedia\PokepediaMoveApi;
use App\Comparator\LevelMoveComparator;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Formatter\PokeApi\MoveFormatter;
use App\Generator\PokepediaMoveGenerator;
use App\Helper\GenerationHelper;
use App\Helper\MoveSetHelper;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ComparePokemonMoveCommand extends Command
{
    protected static $defaultName = 'app:compare';
    protected static $defaultDescription = 'compare movesets';

    private EntityManagerInterface $em;
    private PokepediaMoveApi $api;
    private MoveSetHelper $moveSetHelper;
    private MoveFormatter $pokeApiFormatter;
    private LevelMoveComparator $levelMoveComparator;
    private PokepediaMoveGenerator $generator;
    private GenerationHelper $helper;
    private PdoAdapter $cache;

    public function __construct(EntityManagerInterface $em, PokepediaMoveApi $api, MoveSetHelper $moveSetHelper,
                                MoveFormatter $pokeApiFormatter, LevelMoveComparator $levelMoveComparator,
                                PokepediaMoveGenerator $generator, GenerationHelper $helper, Connection $connection
    )
    {
        $this->em = $em;
        $this->api = $api;
        $this->moveSetHelper = $moveSetHelper;
        $this->pokeApiFormatter = $pokeApiFormatter;
        $this->levelMoveComparator = $levelMoveComparator;
        $this->generator = $generator;
        $this->helper = $helper;
        $this->cache = new PdoAdapter($connection);

        parent::__construct();
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pokemons = $this->em->getRepository(Pokemon::class)->findDefaultAndAlolaPokemons(25);

        $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);

        $generations = $this->em->getRepository(Generation::class)->findAll();

        foreach ($pokemons as $pokemon) {
            foreach ($generations as $generation) {
                $gen = $generation->getGenerationIdentifier();
                if (!$this->helper->isPokemonAvailableInGeneration($pokemon, $generation)) {
                    continue;
                }

                $io->info(sprintf('comparing %s generation %s leveling moves', $pokemon->getName(), $gen));

                $pokepediaMoves = $this->api->getLevelMoves(
                    $this->moveSetHelper->getPokepediaPokemonName($pokemon),
                    $gen
                );
                $pokeApiMoves = $this->pokeApiFormatter->getFormattedLevelPokeApiMoves(
                    $pokemon,
                    $gen,
                    $learnmethod
                );

                if (!$this->levelMoveComparator->levelMoveComparator($pokepediaMoves, $pokeApiMoves)) {
                    //retry one time by deleting cache
                    $this->cache->delete(
                        sprintf('pokepedia.wikitext.%s,%s.%s', $this->moveSetHelper->getPokepediaPokemonName($pokemon), $gen, MoveSetHelper::LEVELING_UP_TYPE));
                    $pokepediaMoves = $this->api->getLevelMoves(
                        $this->moveSetHelper->getPokepediaPokemonName($pokemon),
                        $gen
                    );
                    if (!$this->levelMoveComparator->levelMoveComparator($pokepediaMoves, $pokeApiMoves)) {
                        $this->handleError($learnmethod, $pokemon, $gen, $pokeApiMoves, $io);
                    }
                }
            }
        }
        return Command::SUCCESS;
    }

    private function handleError(?MoveLearnMethod $learnmethod, $pokemon, $gen, array $pokeApiMoves, SymfonyStyle $io)
    {
        $generated = $this->generator->generateMoveWikiText($learnmethod, $pokemon, $gen, $pokeApiMoves);
        $raw = $this->api->getRawWikitext(
            $this->moveSetHelper->getPokepediaPokemonName($pokemon),
            $gen
        );
        file_put_contents('output/generated.txt', $generated);
        file_put_contents('output/raw.txt', $raw);

        passthru('python icdiff.py --strip-trailing-cr -W output/generated.txt output/raw.txt');
        echo PHP_EOL . $generated . PHP_EOL;
        $io->confirm(sprintf('\n\nSkip %s? for generation %s',
            $this->moveSetHelper->getPokepediaPokemonName($pokemon),
            $gen
        ));
    }

}
