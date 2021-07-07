<?php

namespace App\Repository;

use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonMove|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonMove|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonMove[]    findAll()
 * @method PokemonMove[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonMoveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonMove::class);
    }


    public function findMovesByPokemonLearnMethodAndVersionGroup(Pokemon $pokemon,MoveLearnMethod $learnMethod,VersionGroup $versionGroup)
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.pokemon','p')
            ->leftJoin('m.learnMethod','l')
            ->leftJoin('m.versionGroup','v')
            ->andWhere('p.id = :pid')
            ->andWhere('l.id = :lid')
            ->andWhere('v.id = :vid')
            ->setParameter('pid', $pokemon->getId())
            ->setParameter('lid', $learnMethod->getId())
            ->setParameter('vid', $versionGroup->getId())
            ->orderBy('m.level', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?PokemonMove
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
