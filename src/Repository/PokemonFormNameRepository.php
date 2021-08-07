<?php

namespace App\Repository;

use App\Entity\PokemonFormName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonFormName|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonFormName|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonFormName[]    findAll()
 * @method PokemonFormName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonFormNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonFormName::class);
    }

    // /**
    //  * @return PokemonFormName[] Returns an array of PokemonFormName objects
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
    public function findOneBySomeField($value): ?PokemonFormName
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
