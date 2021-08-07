<?php

namespace App\Repository;

use App\Entity\PokedexVersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokedexVersionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokedexVersionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokedexVersionGroup[]    findAll()
 * @method PokedexVersionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokedexVersionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokedexVersionGroup::class);
    }

    // /**
    //  * @return PokedexVersionGroup[] Returns an array of PokedexVersionGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PokedexVersionGroup
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
