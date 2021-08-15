<?php


namespace App\Command;


use App\Helper\MoveSetHelper;
use App\Job\Message\PokemonMoveSynchro;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class StartSynchroCommand extends Command
{
    protected static $defaultName = 'app:start:synchro';

    private MessageBusInterface  $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->bus->dispatch(new PokemonMoveSynchro(MoveSetHelper::LEVELING_UP_TYPE));

        return 0;
    }
}