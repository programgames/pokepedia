<?php

namespace App\Repository;

use App\Entity\PokemonForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

/**
 * @method PokemonForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonForm[]    findAll()
 * @method PokemonForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonForm::class);
    }

    public function findAlolaPokemons()
    {
        $forms = $this
            ->createQueryBuilder('f')
            ->select('f')
            ->andWhere('f.formName = \'alola\'')
            ->getQuery()
            ->getResult();

        $pokemons = array_map(function ($value) {
            return $value->getPokemon();

        }, $forms);
        return $pokemons;
    }

    public function findGen8AlolaPokemons()
    {
        $excludeds = ['rattata-alola', 'raticate-alola', 'geodude-alola', 'graveler-alola', 'golem-alola', 'grimer-alola', 'muk-alola'];
       $forms =  $this
            ->createQueryBuilder('f')
           ->andWhere('f.formName = \'alola\'')
           ->andWhere('f.name NOT IN (:excludeds)')
            ->setParameter('excludeds', $excludeds)
            ->getQuery()
            ->getResult();

        $pokemons = array_map(function ($value) {
            return $value->getPokemon();

        }, $forms);
        return $pokemons;
    }

    public function findGalarPokemons()
    {
        $forms = $this
            ->createQueryBuilder('f')
            ->select('f')
            ->andWhere('f.formName = \'galar\'')
            ->getQuery()
            ->getResult();

        $pokemons = array_map(function ($value) {
            return $value->getPokemon();

        }, $forms);
        return $pokemons;
    }

    /*
    public function findOneBySomeField($value): ?PokemonForm
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
