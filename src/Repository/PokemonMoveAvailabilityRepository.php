<?php

namespace App\Repository;

use App\Entity\Pokedex;
use App\Entity\Pokemon;
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
            ->findOneBy(['name' => 'national']);

        $speciesIds = $this->getEntityManager()->getRepository(PokemonDexNumber::class)
            ->createQueryBuilder('dexNumber')
            ->select('s.id')
            ->leftJoin('dexNumber.pokemonSpecy','s')
            ->leftJoin('dexNumber.pokedex','pokedex')
            ->andWhere('dexNumber.pokedexNumber >= :startAt')
            ->andWhere('pokedex.id = :dexId')
            ->setParameter('startAt', $startAt)
            ->setParameter('dexId', $pokedex->getId())
            ->getQuery()
            ->getResult();
        $speciesIds = array_column($speciesIds,'id');

        $availabilities = $this->createQueryBuilder('availability')
            ->andWhere('availability.hasCustomPokepediaPage = true')
            ->leftJoin('availability.pokemon','pokemon')
            ->orderBy('pokemon.id')
            ->getQuery()
            ->getResult();
        $pokemons = [];

        foreach ($availabilities as $availability) {
            $pokemon = $availability->getPokemon();
            if(in_array($pokemon->getPokemonSpecy()->getId(),$speciesIds))  {
                $pokemons[$pokemon->getId()] = $pokemon;
            }
        }

        return array_values($pokemons);
    }

    public function isPokemonAvailableInVersionGroups(Pokemon $pokemon, array $versionGroups)
    {
        return $this->createQueryBuilder('availability')
            ->leftJoin('availability.versionGroup', 'vg')
            ->leftJoin('availability.pokemon', 'p')
            ->andWhere('vg.name IN (:names)')
            ->andWhere('p.id = :pkm')
            ->setParameter('names', $versionGroups)
            ->setParameter('pkm', $pokemon->getId())
            ->getQuery()
            ->getResult()
            ;
    }
}
