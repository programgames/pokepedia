<?php

namespace App\Repository;

use App\Entity\PokemonAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonAvailability[]    findAll()
 * @method PokemonAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonAvailability::class);
    }

    // /**
    //  * @return PokemonAvailability[] Returns an array of PokemonAvailability objects
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
    public function findOneBySomeField($value): ?PokemonAvailability
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
