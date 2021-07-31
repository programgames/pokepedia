<?php

namespace App\Repository;

use App\Entity\MoveLearnMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoveLearnMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoveLearnMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoveLearnMethod[]    findAll()
 * @method MoveLearnMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveLearnMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveLearnMethod::class);
    }
    
    public function findPokepediaLearnMethod()
    {
        return $this->createQueryBuilder('mlm')
            ->andWhere('mlm.name IN (:mlms)')
            ->setParameter('mlms', ['level-up','tutor','machine','egg'])
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return MoveLearnMethod[] Returns an array of MoveLearnMethod objects
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
    public function findOneBySomeField($value): ?MoveLearnMethod
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
