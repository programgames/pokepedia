<?php

namespace App\Command;

use App\Mapper\MoveMapper;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MapperCommand extends Command
{
    protected static $defaultName = 'app:mapper';
    protected static $defaultDescription = 'Add a short description for your command';

    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stmts = [
            new Namespace_(new Name('App')),
            new Class_('MoveMapper',
                [
                    'stmts' => [new ClassMethod('mapMoves', [
                        'params' => [new Param(new Variable('pokemon'))],
                        'stmts' => []
                    ])
                    ]]
            ),
        ];

        $prettyPrinter = new Standard;
        $newCode = $prettyPrinter->prettyPrintFile($stmts);

        $filename = $this->parameterBag->get('kernel.project_dir') . '\\src\\' . 'MoveMapper.php';
        file_put_contents($filename,$newCode);
    }
}
