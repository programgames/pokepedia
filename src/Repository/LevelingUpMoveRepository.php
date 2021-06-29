<?php

namespace App\Repository;

use App\Entity\LevelingUpMove;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LevelingUpMove|null find($id, $lockMode = null, $lockVersion = null)
 * @method LevelingUpMove|null findOneBy(array $criteria, array $orderBy = null)
 * @method LevelingUpMove[]    findAll()
 * @method LevelingUpMove[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LevelingUpMoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LevelingUpMove::class);
    }

    // /**
    //  * @return LevelingUpMove[] Returns an array of LevelingUpMove objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LevelingUpMove
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
