<?php

namespace App\Repository;

use App\Entity\PokemonFormGeneration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonFormGeneration|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonFormGeneration|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonFormGeneration[]    findAll()
 * @method PokemonFormGeneration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonFormGenerationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonFormGeneration::class);
    }

    // /**
    //  * @return PokemonFormGeneration[] Returns an array of PokemonFormGeneration objects
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
    public function findOneBySomeField($value): ?PokemonFormGeneration
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
