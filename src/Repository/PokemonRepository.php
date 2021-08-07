<?php

namespace App\Repository;

use App\Entity\Pokedex;
use App\Entity\Pokemon;
use App\Entity\PokemonDexNumber;
use App\Entity\PokemonForm;
use App\Entity\PokemonSpecy;
use App\Entity\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function findDefaultPokemonsInNationalPokedex(int $start,int $end)
    {
        $pokedex = $this->getEntityManager()->getRepository(Pokedex::class)
            ->findOneBy(['name' => 'national']);

        $specyIds = $this->getEntityManager()->getRepository(PokemonDexNumber::class)
            ->createQueryBuilder('d')
            ->select('s.id')
            ->leftJoin('d.pokemonSpecy','s')
            ->leftJoin('d.pokedex','pokedex')
            ->andWhere('d.pokedexNumber >= :start and d.pokedexNumber <= :end')
            ->andWhere('pokedex.id = :pokedexId')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->setParameter('pokedexId', $pokedex->getId())
            ->getQuery()
            ->getArrayResult();

        $specyIds = array_column($specyIds, "id");

        return $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy', 's')
            ->andWhere('p.isDefault = true')
            ->andWhere('s.id IN (:sids)')
            ->setParameter('sids', $specyIds)
            ->getQuery()
            ->getResult();
    }


}
