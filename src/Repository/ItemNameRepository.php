<?php

namespace App\Repository;

use App\Entity\ItemName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ItemName|null find($id, $lockMode = null, $lockVersion = null)
 * @method ItemName|null findOneBy(array $criteria, array $orderBy = null)
 * @method ItemName[]    findAll()
 * @method ItemName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemName::class);
    }
}
