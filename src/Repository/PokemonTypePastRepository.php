<?php

namespace App\Repository;

use App\Entity\PokemonTypePast;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonTypePast|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonTypePast|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonTypePast[]    findAll()
 * @method PokemonTypePast[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonTypePastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonTypePast::class);
    }

    // /**
    //  * @return PokemonTypePast[] Returns an array of PokemonTypePast objects
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
    public function findOneBySomeField($value): ?PokemonTypePast
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
