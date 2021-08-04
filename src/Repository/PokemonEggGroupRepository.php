<?php

namespace App\Repository;

use App\Entity\PokemonEggGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonEggGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonEggGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonEggGroup[]    findAll()
 * @method PokemonEggGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonEggGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonEggGroup::class);
    }

    // /**
    //  * @return PokemonEggGroup[] Returns an array of PokemonEggGroup objects
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
    public function findOneBySomeField($value): ?PokemonEggGroup
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
