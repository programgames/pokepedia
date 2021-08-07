<?php

namespace App\Repository;

use App\Entity\TypeName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeName|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeName|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeName[]    findAll()
 * @method TypeName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeName::class);
    }

    // /**
    //  * @return TypeName[] Returns an array of TypeName objects
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
    public function findOneBySomeField($value): ?TypeName
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
