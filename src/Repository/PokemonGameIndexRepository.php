<?php

namespace App\Repository;

use App\Entity\PokemonGameIndex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonGameIndex|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonGameIndex|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonGameIndex[]    findAll()
 * @method PokemonGameIndex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonGameIndexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonGameIndex::class);
    }

    // /**
    //  * @return PokemonGameIndex[] Returns an array of PokemonGameIndex objects
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
    public function findOneBySomeField($value): ?PokemonGameIndex
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
