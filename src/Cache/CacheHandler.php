<?php


namespace App\Cache;


use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;

class CacheHandler
{
    private PdoAdapter $cache;
    private EntityManagerInterface $em;

    public function __construct(Connection $connection,EntityManagerInterface $em)
    {
        $this->cache = new PdoAdapter($connection);
        $this->em = $em;
    }

    public function deleteCache()
    {
        $this->cache->clear();
    }
}