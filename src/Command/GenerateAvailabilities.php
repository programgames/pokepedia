<?php


namespace App\Command;


use App\Handler\AvailabilityByGenerationHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $this->handler->handleAvailablities();

        return Command::SUCCESS;
    }
}
