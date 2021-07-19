<?php


namespace App\Command;


use App\Api\Bulbapedia\BulbapediaAvailabilityAPI;
use App\Entity\Pokemon;
use App\Handler\AvailabilityByGenerationHandler;
use App\Helper\AvailabilityHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportBulbapediaAvailabilities extends Command
{
    protected static $defaultName = 'app:import:bulbapedia:availabilities';
    protected static $defaultDescription = 'Import bulbapedia availabilities';

    private BulbapediaAvailabilityAPI $api;
    private EntityManagerInterface $em;
    private AvailabilityByGenerationHandler $handler;

    public function __construct(BulbapediaAvailabilityAPI $api, EntityManagerInterface $em,AvailabilityByGenerationHandler $handler)
    {
        parent::__construct();
        $this->api = $api;
        $this->em = $em;
        $this->handler = $handler;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $pokemonRepository = $this->em->getRepository(Pokemon::class);


        foreach (AvailabilityHelper::getBulbapediaAvailabilitiesGenerationSectionsName() as $generation) {

            foreach ($this->api->getAvailabilitiesByGeneration($generation) as $availabilities) {
                $availabilities = explode('|',$availabilities);
                $pokemon = $this->em->getRepository(Pokemon::class)->findOneByBPIndex($availabilities[1]);
                $this->handler->handleAvailablities($pokemon,$generation,$availabilities);
            }
            $this->em->flush();
        }
        return Command::SUCCESS;
    }
}
