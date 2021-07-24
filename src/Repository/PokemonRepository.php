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

    public function findDefaultPokemons(int $start,int $end)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy','s')
            ->andWhere('s.pokemonSpeciesOrder >= :start AND s.pokemonSpeciesOrder <= :end')
            ->andWhere('p.isDefault = true')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('s.pokemonSpeciesOrder', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAlolaPokemons()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isAlola = true')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findGalarPokemon()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isGalar = true')
            ->getQuery()
            ->getResult()
            ;
    }
}
