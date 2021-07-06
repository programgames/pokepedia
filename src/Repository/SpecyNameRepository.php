<?php

namespace App\Repository;

use App\Entity\SpecyName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpecyName|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpecyName|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpecyName[]    findAll()
 * @method SpecyName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecyNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpecyName::class);
    }

    // /**
    //  * @return SpecyName[] Returns an array of SpecyName objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpecyName
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
