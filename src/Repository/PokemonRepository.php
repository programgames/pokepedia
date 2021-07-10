<?php

namespace App\Repository;

use App\Entity\Pokemon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pokemon|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pokemon|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pokemon[]    findAll()
 * @method Pokemon[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokemon::class);
    }

    public function findLGPEPokemons()
    {
        $gen1 =  $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy','s')
            ->andWhere('p.pokemonIdentifier >= 1 AND p.pokemonIdentifier <= 151')
            ->andWhere('p.toImport = TRUE')
            ->orderBy('s.pokemonSpeciesOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $news = $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy','s')
            ->andWhere('p.name = \'melmetal\'')
            ->orWhere('p.name = \'meltan\'')
            ->andWhere('p.toImport = \'TRUE\'')
            ->orderBy('s.pokemonSpeciesOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return array_merge($gen1,$news);
    }

    /*
    public function findOneBySomeField($value): ?Pokemon
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
