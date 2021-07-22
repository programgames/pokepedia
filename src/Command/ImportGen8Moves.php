<?php


namespace App\Command;


use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\PokemonAvailability;
use App\Entity\VersionGroup;
use App\Helper\MoveSetHelper;
use App\MoveMapper;
use App\Processor\BulbapediaMoveProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportGen8Moves extends Command
{
    protected static $defaultName = 'app:import:gen8';
    protected static $defaultDescription = 'import bulbapedia gen8 movesets';

    private BulbapediaMoveProcessor $processor;

    public function __construct(BulbapediaMoveProcessor $processor)
    {
        parent::__construct();

        $this->processor = $processor;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->processor->importMoveByGeneration(8,$io);

        return Command::SUCCESS;
    }
}
