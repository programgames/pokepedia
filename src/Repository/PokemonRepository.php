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

    public function findDefaultPokemons(int $start, int $end)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy', 's')
            ->andWhere('s.pokemonSpecyOrder >= :start AND s.pokemonSpecyOrder <= :end')
            ->andWhere('p.isDefault = true')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('s.pokemonSpecyOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findDefaultAndAlolaPokemons($startAt = null)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy', 's')
            ->andWhere('p.isDefault = true OR p.isAlola = true')
            ->andWhere('s.pokemonSpecyOrder >= :startAt')
            ->andWhere('p.name NOT LIKE :totem')
            ->orderBy('s.pokemonSpecyOrder', 'ASC')
            ->setParameter('startAt', $startAt ?? 1)
            ->setParameter('totem', '%totem%')
            ->getQuery()
            ->getResult();
    }

    public function findAlolaPokemons()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isAlola = true')
            ->getQuery()
            ->getResult();
    }

    public function findGalarPokemon()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isGalar = true')
            ->getQuery()
            ->getResult();
    }
}
