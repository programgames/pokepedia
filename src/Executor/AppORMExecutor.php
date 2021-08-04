<?php


namespace App\Executor;


use App\Entity\DataFixture;
use Doctrine\Common\DataFixtures\Event\Listener\ORMReferenceListener;
use Doctrine\Common\DataFixtures\Executor\AbstractExecutor;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class responsible for executing data fixtures.
 */
class AppORMExecutor extends AbstractExecutor
{
    /** @var EntityManagerInterface */
    private $em;
    private ORMReferenceListener $listener;

    /**
     * Construct new fixtures loader instance.
     *
     * @param EntityManagerInterface $em EntityManagerInterface instance used for persistence.
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct($em);
        $this->listener = new ORMReferenceListener($this->referenceRepository);
        $em->getEventManager()->addEventSubscriber($this->listener);
    }

    /**
     * Retrieve the EntityManagerInterface instance this executor instance is using.
     *
     * @return EntityManagerInterface
     */
    public function getObjectManager()
    {
        return $this->em;
    }

    /** @inheritDoc */
    public function setReferenceRepository(ReferenceRepository $referenceRepository)
    {
        $this->em->getEventManager()->removeEventListener(
            $this->listener->getSubscribedEvents(),
            $this->listener
        );

        $this->referenceRepository = $referenceRepository;
        $this->listener            = new ORMReferenceListener($this->referenceRepository);
        $this->em->getEventManager()->addEventSubscriber($this->listener);
    }

    /** @inheritDoc */
    public function execute(array $fixtures, $append = false)
    {
        $executor = $this;
        $this->em->transactional(static function (EntityManagerInterface $em) use ($executor, $fixtures) {
            foreach ($fixtures as $fixture) {
                $executor->load($em, $fixture);
                $fixtureEntity = new DataFixture();
                $fixtureEntity->setName(get_class($fixture));
                $em->persist($fixtureEntity);
                $em->flush();
            }
        });
    }
}
