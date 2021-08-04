<?php

namespace App\Repository;

use App\Entity\DataFixture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DataFixture|null find($id, $lockMode = null, $lockVersion = null)
 * @method DataFixture|null findOneBy(array $criteria, array $orderBy = null)
 * @method DataFixture[]    findAll()
 * @method DataFixture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DataFixtureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DataFixture::class);
    }

    // /**
    //  * @return DataFixture[] Returns an array of DataFixture objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DataFixture
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
