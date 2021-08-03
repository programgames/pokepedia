<?php

namespace App\Repository;

use App\Entity\PokemonName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonName|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonName|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonName[]    findAll()
 * @method PokemonName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonName::class);
    }

    public function findPokemonByBulbapediaName(string $name)
    {
        return $this->createQueryBuilder('pn')
            ->andWhere('pn.bulbapediaName = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
