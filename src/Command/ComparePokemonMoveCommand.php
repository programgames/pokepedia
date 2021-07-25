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

    public function __construct(
        EntityManagerInterface $em,
        PokepediaMoveApi $api,
        MoveSetHelper $moveSetHelper,
        PokeApiTutorMoveFormatter $pokeApiFormatter,
        LevelMoveComparator $levelMoveComparator,
        PokepediaMoveGenerator $generator
    )
    {
        parent::__construct();

        $this->em = $em;
        $this->api = $api;
        $this->moveSetHelper = $moveSetHelper;
        $this->pokeApiFormatter = $pokeApiFormatter;
        $this->levelMoveComparator = $levelMoveComparator;
        $this->generator = $generator;
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
                if (!$this->isPokemonAvailableInGeneration($pokemon, $generation)) {
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
                try {
                    $this->levelMoveComparator->levelMoveComparator($pokepediaMoves, $pokeApiMoves);
                } catch (\RuntimeException $exception) {
                    $io->error($exception->getMessage());
                    $wikiText = $this->generator->generateMoveWikiText($learnmethod,$pokemon,$gen,$pokeApiMoves);
                    $output->write($wikiText);
                    $io->confirm('Press any character and enter to continue');
                }
            }
        }
        return Command::SUCCESS;
    }

    private function isPokemonAvailableInGeneration(Pokemon $pokemon, Generation $generation)
    {
        $availabilityRepository = $this->em->getRepository(PokemonAvailability::class);

        $available = false;
        $gen = $generation->getGenerationIdentifier();
        switch ($gen) {
            case 1:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups($pokemon,
                    ['red-blue', 'yellow']);
                break;
            case 2:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups($pokemon,
                    ['gold-silver', 'crystal']);
                break;
            case 3:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups($pokemon,
                    ['ruby-sapphire', 'emerald', 'firered-leafgreen']);
                break;
            case 4:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups($pokemon,
                    ['diamond-pearl', 'platinum', 'heartgold-soulsilver']);
                break;
            case 5:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups($pokemon,
                    ['black-white', 'black-2-white-2']);
                break;
            case 6:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups($pokemon,
                    ['x-y', 'omega-ruby-alpha-sapphire']);
                break;
            case 7:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups($pokemon,
                    ['sun-moon', 'ultra-sun-ultra-moon', 'lets-go']);
                break;

        }
        return !empty($available);

    }
}