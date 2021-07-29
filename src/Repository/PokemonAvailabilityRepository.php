<?php

namespace App\Repository;

use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Entity\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonAvailability|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonAvailability|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonAvailability[]    findAll()
 * @method PokemonAvailability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonAvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonAvailability::class);
    }

    public function isPokemonAvailableInVersionGroups(Pokemon $pokemon,array $versionGroups)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.versionGroup','vg')
            ->leftJoin('a.pokemon','p')
            ->andWhere('vg.name IN (:names)')
            ->andWhere('p.id = :pkm')
            ->setParameter('names', $versionGroups)
            ->setParameter('pkm', $pokemon->getId())
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?PokemonAvailability
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
