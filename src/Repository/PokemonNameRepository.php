<?php

namespace App\Repository;

use App\Entity\PokemonName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonName|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonName|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonName[]    findAll()
 * @method PokemonName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonName::class);
    }

    // /**
    //  * @return PokemonName[] Returns an array of PokemonName objects
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
    public function findOneBySomeField($value): ?PokemonName
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
