<?php

namespace App\Repository;

use App\Entity\MoveEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoveEntity|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoveEntity|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoveEntity[]    findAll()
 * @method MoveEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveEntity::class);
    }

    // /**
    //  * @return MoveEntity[] Returns an array of MoveEntity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MoveEntity
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
