<?php

namespace App\Command;

use App\Builder\MoveSetMapperBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Yaml\Yaml;

class MapperCommand extends Command
{
    protected static $defaultName = 'app:mapper';
    protected static $defaultDescription = 'Generate mapper';

    private ParameterBagInterface $parameterBag;
    private MoveSetMapperBuilder $moveSetMapperBuilder;

    public function __construct(ParameterBagInterface $parameterBag, MoveSetMapperBuilder $moveSetMapperBuilder)
    {
        parent::__construct();
        $this->parameterBag = $parameterBag;
        $this->moveSetMapperBuilder = $moveSetMapperBuilder;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $config = Yaml::parseFile($this->parameterBag->get('kernel.project_dir') . '/config/movemapping.yml');

        $filename = $this->parameterBag->get('kernel.project_dir') . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'MoveMapper.php';
        file_put_contents($filename, $this->moveSetMapperBuilder->getMapperCode($config));

        return Command::SUCCESS;
    }


}