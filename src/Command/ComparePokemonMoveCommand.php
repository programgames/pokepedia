<?php


namespace App\Command;


use App\Api\Pokepedia\PokepediaMoveApi;
use App\Comparator\LevelMoveComparator;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Formatter\PokeApi\PokeApiTutorMoveFormatter;
use App\Generator\PokepediaMoveGenerator;
use App\Helper\GenerationHelper;
use App\Helper\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
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
    private PokeApiTutorMoveFormatter $pokeApiFormatter;
    private LevelMoveComparator $levelMoveComparator;
    private PokepediaMoveGenerator $generator;
    private GenerationHelper $helper;

    /**
     * ComparePokemonMoveCommand constructor.
     * @param EntityManagerInterface $em
     * @param PokepediaMoveApi $api
     * @param MoveSetHelper $moveSetHelper
     * @param PokeApiTutorMoveFormatter $pokeApiFormatter
     * @param LevelMoveComparator $levelMoveComparator
     * @param PokepediaMoveGenerator $generator
     * @param GenerationHelper $helper
     */
    public function __construct(EntityManagerInterface $em, PokepediaMoveApi $api, MoveSetHelper $moveSetHelper, PokeApiTutorMoveFormatter $pokeApiFormatter, LevelMoveComparator $levelMoveComparator, PokepediaMoveGenerator $generator, GenerationHelper $helper)
    {
        $this->em = $em;
        $this->api = $api;
        $this->moveSetHelper = $moveSetHelper;
        $this->pokeApiFormatter = $pokeApiFormatter;
        $this->levelMoveComparator = $levelMoveComparator;
        $this->generator = $generator;
        $this->helper = $helper;

        parent::__construct();

    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pokemons = $this->em->getRepository(Pokemon::class)->findDefaultAndAlolaPokemons();

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
                    $this->handleErrror($learnmethod, $pokemon, $gen, $pokeApiMoves, $io);
                }
            }
        }
        return Command::SUCCESS;
    }

    private function handleErrror(?MoveLearnMethod $learnmethod, $pokemon, $gen, array $pokeApiMoves, SymfonyStyle $io)
    {
        $generated = $this->generator->generateMoveWikiText($learnmethod, $pokemon, $gen, $pokeApiMoves);
        $raw = $this->api->getRawWikitext(
            $this->moveSetHelper->getPokepediaPokemonName($pokemon),
            $gen
        );
        $io->block($generated);
        file_put_contents('output/generated.txt', $generated);
        file_put_contents('output/raw.txt', $raw);
        // not rly proud of this but this is working :)
        passthru('phpstorm64.exe diff .\output\generated.txt .\output\raw.txt');

        $io->confirm('\n\nPress any character and enter to continue');
    }

}