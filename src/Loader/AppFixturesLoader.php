<?php

namespace App\Loader;

use App\Entity\DataFixture;
use Doctrine\Bundle\FixturesBundle\DependencyInjection\CompilerPass\FixturesCompilerPass;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class AppFixturesLoader extends ContainerAwareLoader
{
    /** @var FixtureInterface[] */
    private $loadedFixtures = [];

    /** @var array<string, array<string, bool>> */
    private $groupsFixtureMapping = [];

    private EntityManagerInterface $em;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        parent::__construct($container);
        $this->em = $em;
    }


    /**
     * @internal
     */
    public function addFixtures(array $fixtures): void
    {
        // Because parent::addFixture may call $this->createFixture
        // we cannot call $this->addFixture in this loop
        foreach ($fixtures as $class => $fixture) {
            $class = get_class($fixture['fixture']);
            $this->loadedFixtures[$class] = $fixture['fixture'];
            $this->addGroupsFixtureMapping($class, $fixture['groups']);
        }

        // Now that all fixtures are in the $this->loadedFixtures array,
        // it is safe to call $this->addFixture in this loop
        foreach ($this->loadedFixtures as $class => $fixture) {
            $this->addFixture($fixture);
        }
    }

    public function addFixture(FixtureInterface $fixture): void
    {
        $class = get_class($fixture);
        $this->loadedFixtures[$class] = $fixture;

        $reflection = new \ReflectionClass($fixture);
        $this->addGroupsFixtureMapping($class, [$reflection->getShortName()]);

        if ($fixture instanceof FixtureGroupInterface) {
            $this->addGroupsFixtureMapping($class, $fixture::getGroups());
        }

        parent::addFixture($fixture);
    }

    protected function createFixture($class): FixtureInterface
    {
        /*
         * We don't actually need to create the fixture. We just
         * return the one that already exists.
         */

        if (!isset($this->loadedFixtures[$class])) {
            throw new \LogicException(sprintf(
                'The "%s" fixture class is trying to be loaded, but is not available. Make sure this class is defined as a service and tagged with "%s".',
                $class,
                FixturesCompilerPass::FIXTURE_TAG
            ));
        }

        return $this->loadedFixtures[$class];
    }

    /**
     * Returns the array of data fixtures to execute.
     *
     * @param string[] $groups
     *
     * @return FixtureInterface[]
     */
    public function getFixtures(array $groups = []): array
    {
        $fixtures = parent::getFixtures();
        $fixtures = $this->filterInstalledFixtures($fixtures);
        if (empty($groups)) {
            return $fixtures;
        }

        $filteredFixtures = [];
        foreach ($fixtures as $fixture) {
            foreach ($groups as $group) {
                $fixtureClass = get_class($fixture);
                if (isset($this->groupsFixtureMapping[$group][$fixtureClass])) {
                    $filteredFixtures[$fixtureClass] = $fixture;
                    continue 2;
                }
            }
        }

        foreach ($filteredFixtures as $fixture) {
            $this->validateDependencies($filteredFixtures, $fixture);
        }

        return array_values($filteredFixtures);
    }

    /**
     * Generates an array of the groups and their fixtures
     *
     * @param string[] $groups
     */
    private function addGroupsFixtureMapping(string $className, array $groups): void
    {
        foreach ($groups as $group) {
            $this->groupsFixtureMapping[$group][$className] = true;
        }
    }

    /**
     * @param string[] $fixtures An array of fixtures with class names as keys
     *
     * @throws \RuntimeException
     */
    private function validateDependencies(array $fixtures, FixtureInterface $fixture): void
    {
        if (!$fixture instanceof DependentFixtureInterface) {
            return;
        }

        $dependenciesClasses = $fixture->getDependencies();

        foreach ($dependenciesClasses as $class) {
            if (!array_key_exists($class, $fixtures)) {
                throw new \RuntimeException(sprintf('Fixture "%s" was declared as a dependency for fixture "%s", but it was not included in any of the loaded fixture groups.', $class, get_class($fixture)));
            }
        }
    }

    private function filterInstalledFixtures(array $fixtures)
    {
        $fixtureRepository = $this->em->getRepository(DataFixture::class);

        $todoFixtures = [];
        foreach ($fixtures as $fixture) {
            if($fixtureRepository->findOneBy(['name' => get_class($fixture)])) {
                continue;
            }
            $todoFixtures[] = $fixture;
        }

        return $todoFixtures;
    }
}
