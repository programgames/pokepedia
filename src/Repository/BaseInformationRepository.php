<?php

namespace App\Repository;

use App\Entity\BaseInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseInformation[]    findAll()
 * @method BaseInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseInformation::class);
    }

    // /**
    //  * @return BaseInformation[] Returns an array of BaseInformation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BaseInformation
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
