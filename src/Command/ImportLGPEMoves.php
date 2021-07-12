<?php


namespace App\Command;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\MoveMapper;
use Doctrine\ORM\EntityManagerInterface;
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

    /**
     * ImportLGPEMoves constructor.
     * @param EntityManagerInterface $em
     * @param BulbapediaMovesAPI $api
     */
    public function __construct(EntityManagerInterface $em, BulbapediaMovesAPI $api)
    {
        parent::__construct();

        $this->em = $em;
        $this->api = $api;
    }


    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $moveMapper = new MoveMapper();
        $repository = $this->em->getRepository(MoveName::class);
        $io = new SymfonyStyle($input, $output);

        $pokemons = $this->em->getRepository(Pokemon::class)->findLGPEPokemons();

        $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);

        $generations = $this->em->getRepository(Generation::class)->findAll();

        foreach ($pokemons as $pokemon) {

            $moves = $this->api->getLevelMoves($pokemon, 7, true);
            if (array_key_exists('noform', $moves)) {
                foreach ($moves['noform'] as $move) {
                    if ($move['format'] === 'numeral') {
                        $moveMapper->mapMoves($pokemon,$move);
//                        $moveNameEntity = $repository->findOneBy([
//                            'name' => $move['value'][2],
//                            'language' => 9
//                        ]);
//                        if (!$moveNameEntity) {
//                            throw new \RuntimeException(sprintf('MoveName not found %s', $move['value'][2]));
//                        }
//                        $pokemonMove = new PokemonMove();
//                        $pokemonMove->setLevel($move['value'][1]);
//                        $pokemonMove->setMove($moveNameEntity->getMove());
//                        $pokemonMove->setLearnMethod($learnmethod);
//                        $pokemonMove->setPokemon($pokemon);
//                        $this->em->persist($pokemonMove);
                    }
                    else {
                        throw new \RuntimeException('Format roman');
                    }
                }
            }

        }

        return Command::SUCCESS;
    }
}
