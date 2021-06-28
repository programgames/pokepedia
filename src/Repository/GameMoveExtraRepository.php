<?php

namespace App\Repository;

use App\Entity\GameMoveExtra;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GameMoveExtra|null find($id, $lockMode = null, $lockVersion = null)
 * @method GameMoveExtra|null findOneBy(array $criteria, array $orderBy = null)
 * @method GameMoveExtra[]    findAll()
 * @method GameMoveExtra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameMoveExtraRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameMoveExtra::class);
    }

    // /**
    //  * @return GameMoveExtra[] Returns an array of GameMoveExtra objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GameMoveExtra
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
