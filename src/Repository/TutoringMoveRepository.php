<?php

namespace App\Repository;

use App\Entity\TutoringMove;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TutoringMove|null find($id, $lockMode = null, $lockVersion = null)
 * @method TutoringMove|null findOneBy(array $criteria, array $orderBy = null)
 * @method TutoringMove[]    findAll()
 * @method TutoringMove[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TutoringMoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TutoringMove::class);
    }

    // /**
    //  * @return TutoringMove[] Returns an array of TutoringMove objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TutoringMove
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
