<?php

namespace App\Repository;

use App\Entity\MoveAlias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoveAlias|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoveAlias|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoveAlias[]    findAll()
 * @method MoveAlias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveAliasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveAlias::class);
    }

    // /**
    //  * @return MoveAlias[] Returns an array of MoveAlias objects
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
    public function findOneBySomeField($value): ?MoveAlias
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
