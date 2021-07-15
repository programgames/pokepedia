<?php


namespace App\Command;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;
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
        $io = new SymfonyStyle($input, $output);

        $pokemons = $this->em->getRepository(Pokemon::class)->findLGPEPokemons();

        $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);

        $generation = $this->em->getRepository(Generation::class)->findOneBy(
            [
                'generationIdentifier' => 7
            ]
        );

        foreach ($pokemons as $pokemon) {

            if($pokemon->getName() != 'venusaur') {
                continue;
            }
            $io->info(sprintf('import tutoring move for LGPE %s',$pokemon->getName()));
            $moves = $this->api->getLevelMoves($pokemon, 7, true);
            if (array_key_exists('noform', $moves)) {
                foreach ($moves['noform'] as $move) {
                    if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                        $moveMapper->mapMoves($pokemon,$move,$generation,$this->em,$learnmethod);
                    }
                    else {
                        throw new \RuntimeException('Format roman');
                    }
                }
//                $this->em->flush();
            }

        }

        return Command::SUCCESS;
    }
}
