<?php


namespace App\Command;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportLGPEMoves extends Command
{
    protected static $defaultName = 'app:import:bulbapedia:moveset:lgpe';
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
        $io = new SymfonyStyle($input, $output);

        $pokemons = $this->em->getRepository(Pokemon::class)->findLGPEPokemons();

        $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);

        $generations = $this->em->getRepository(Generation::class)->findAll();

        foreach ($pokemons as $pokemon) {

           $moves =  $this->api->getLevelMoves($pokemon,7);

        }

        return Command::SUCCESS;
    }
}
