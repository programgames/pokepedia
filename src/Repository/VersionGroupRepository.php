<?php

namespace App\Repository;

use App\Entity\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VersionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersionGroup[]    findAll()
 * @method VersionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersionGroup::class);
    }


    public function findPokepediaVersionGroup()
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.name = IN :val')
            ->setParameter('vgs', [])
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?VersionGroup
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
