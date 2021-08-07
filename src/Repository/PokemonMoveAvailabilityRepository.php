<?php

namespace App\Repository;

use App\Entity\Pokedex;
use App\Entity\PokemonDexNumber;
use App\Entity\PokemonMoveAvailability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonMoveAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonMoveAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonMoveAvailability[]    findAll()
 * @method PokemonMoveAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonMoveAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonMoveAvailability::class);
    }


    public function findPokemonWithSpecificPageStartingAt(int $startAt)
    {
        $pokedex = $this->getEntityManager()->getRepository(Pokedex::class)
            ->findOneBy(['name'  => 'national']);

        $availabilities = $this->createQueryBuilder('availability')
            ->andWhere('availability.hasCustomPokepediaPage = true')
            ->distinct('availability.pokemon')
            ->getQuery()
            ->getResult();

        $pokemons = [];
        foreach ($availabilities as $availability) {
            $dexEntry =$this->getEntityManager()->getRepository(PokemonDexNumber::class)
                ->createQueryBuilder('dex')
                ->leftJoin('dex.pokemonSpecy','specy')
                ->leftJoin('dex.pokedex','pokedex')
                ->andWhere('dex.pokemonNumber >= :startAt')
                ->andWhere('pokedex.id = :pokedexId')
                ->setParameter('pokedexId',$pokedex->getId())
                ->setParameter('startAt',$startAt)
                ->getQuery()
                ->getOneOrNullResult();
            if($dexEntry) {
                $pokemons = $availability->getPokemon();
            }
        }

        return $pokemons;
    }
}
