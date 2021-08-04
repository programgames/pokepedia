<?php


namespace App\Command;


use App\Executor\AppORMExecutor;
use App\Loader\AppFixturesLoader;
use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Doctrine\DBAL\Sharding\PoolingShardConnection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * Load data fixtures from bundles.
 */
class LoadAppFixturesCommand extends DoctrineCommand
{
    protected static $defaultName = 'app:fixture:load';

    private AppFixturesLoader $fixturesLoader;

    private ParameterBagInterface $parameterBag;

    public function __construct(AppFixturesLoader $fixturesLoader,ParameterBagInterface $parameterBag, ?ManagerRegistry $doctrine = null)
    {
        if ($doctrine === null) {
            @trigger_error(sprintf(
                'Argument 2 of %s() expects an instance of %s, not passing it will throw a \TypeError in DoctrineFixturesBundle 4.0.',
                __METHOD__,
                ManagerRegistry::class
            ), E_USER_DEPRECATED);
        }

        parent::__construct($doctrine);

        $this->fixturesLoader  = $fixturesLoader;
        $this->parameterBag  = $parameterBag;
    }

    // phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    protected function configure()
    {
        $this
            ->setName('app:fixtures:load')
            ->setDescription('Load data fixtures to your database')
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
            ->addOption('group', null, InputOption::VALUE_IS_ARRAY|InputOption::VALUE_REQUIRED, 'Only load fixtures that belong to this group')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.')
            ->addOption('shard', null, InputOption::VALUE_REQUIRED, 'The shard connection to use for this command.')
            ->setHelp(<<<EOT
The <info>%command.name%</info> command loads data fixtures from your application:

  <info>php %command.full_name%</info>

Fixtures are services that are tagged with <comment>doctrine.fixture.orm</comment>.

To execute only fixtures that live in a certain group, use:

  <info>php %command.full_name%</info> <comment>--group=group1</comment>

EOT
            );
    }

    /**
     * @return int
     */
    // phpcs:ignore SlevomatCodingStandard.TypeHints.TypeHintDeclaration.MissingReturnTypeHint
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ui = new SymfonyStyle($input, $output);

        $em = $this->getDoctrine()->getManager($input->getOption('em'));

        if ($input->getOption('shard')) {
            if (! $em->getConnection() instanceof PoolingShardConnection) {
                throw new \LogicException(sprintf(
                    'Connection of EntityManager "%s" must implement shards configuration.',
                    $input->getOption('em')
                ));
            }

            $em->getConnection()->connect($input->getOption('shard'));
        }

        $groups   = $input->getOption('group');
        $this->fixturesLoader->loadFromDirectory($this->parameterBag->get('kernel.project_dir') . '/src/DataFixtures');
        $fixtures = $this->fixturesLoader->getFixtures($groups);
        if (! $fixtures) {
            $message = 'Could not find any fixture services to load';

            if (! empty($groups)) {
                $message .= sprintf(' in the groups (%s)', implode(', ', $groups));
            }

            $ui->error($message . '.');

            return 1;
        }


        $executor = new AppORMExecutor($em);
        $executor->setLogger(static function ($message) use ($ui) : void {
            $ui->text(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });
        $executor->execute($fixtures);

        return 0;
    }
}