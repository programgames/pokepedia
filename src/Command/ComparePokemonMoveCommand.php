<?php


namespace App\Command;


use App\Api\Pokepedia\PokepediaMoveApi;
use App\Comparator\LevelMoveComparator;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Formatter\PokeApiMoveFormatter;
use App\Helper\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ComparePokemonMoveCommand extends Command
{
    protected static $defaultName = 'app:compare:pokepedia:moveset';
    protected static $defaultDescription = 'compare movesets';

    private EntityManagerInterface $em;
    private PokepediaMoveApi $api;
    private MoveSetHelper $moveSetHelper;
    private PokeApiMoveFormatter $pokeApiFormatter;
    private LevelMoveComparator $levelMoveComparator;

    /**
     * ComparePokemonMoveCommand constructor.
     * @param EntityManagerInterface $em
     * @param PokepediaMoveApi $api
     * @param MoveSetHelper $moveSetHelper
     * @param PokeApiMoveFormatter $pokeApiFormatter
     * @param LevelMoveComparator $levelMoveComparator
     */
    public function __construct(EntityManagerInterface $em, PokepediaMoveApi $api, MoveSetHelper $moveSetHelper, PokeApiMoveFormatter $pokeApiFormatter, LevelMoveComparator $levelMoveComparator)
    {
        parent::__construct();

        $this->em = $em;
        $this->api = $api;
        $this->moveSetHelper = $moveSetHelper;
        $this->pokeApiFormatter = $pokeApiFormatter;
        $this->levelMoveComparator = $levelMoveComparator;
    }


    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pokemons = $this->em->getRepository(Pokemon::class)->findBy(
            [
                'toImport' => true
            ]);

        $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);
        foreach ($pokemons as $pokemon) {
            $pokepediaMoves = $this->api->getLevelMoves($this->moveSetHelper->getPokepediaPokemonName($pokemon), 1);
            $pokeApiMoves = $this->pokeApiFormatter->getPokeApiMoves($pokemon, 1,$learnmethod);
            $this->levelMoveComparator->levelMoveComparator($pokepediaMoves,$pokeApiMoves);
        }

        return Command::SUCCESS;
    }
}