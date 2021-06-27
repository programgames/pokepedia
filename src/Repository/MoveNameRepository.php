<?php

namespace App\Repository;

use App\Entity\MoveName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoveName|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoveName|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoveName[]    findAll()
 * @method MoveName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveName::class);
    }

    // /**
    //  * @return Move[] Returns an array of Move objects
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
    public function findOneBySomeField($value): ?Move
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
