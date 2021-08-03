<?php

namespace App\Repository;

use App\Entity\EggGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EggGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method EggGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method EggGroup[]    findAll()
 * @method EggGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EggGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EggGroup::class);
    }
}
