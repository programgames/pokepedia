<?php


namespace App\Command\Installation;


use App\Handler\AvailabilityByGenerationHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateAvailabilities extends Command
{
    protected static $defaultName = 'app:generate:availabilities';
    protected static $defaultDescription = 'generate availabilities';

    private AvailabilityByGenerationHandler $handler;

    public function __construct(AvailabilityByGenerationHandler $handler)
    {
        parent::__construct();
        $this->handler = $handler;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Generating availabilities');

        $this->handler->handleAvailablities();

        $io->info('Availabilities generated');

        return Command::SUCCESS;
    }
}
