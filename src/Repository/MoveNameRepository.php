<?php

namespace App\Repository;

use App\Entity\MoveName;
use App\Entity\PokemonMove;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MoveName|null find($id, $lockMode = null, $lockVersion = null)
 * @method MoveName|null findOneBy(array $criteria, array $orderBy = null)
 * @method MoveName[]    findAll()
 * @method MoveName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MoveNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MoveName::class);
    }

    public function findAndFormatMoveNameByPokemonMove(PokemonMove $pokemonMove)
    {
        $moveName =  $this->createQueryBuilder('move_name')
            ->leftJoin('m.move','move' )
            ->andWhere('move_name.language = 5')
            ->andWhere('move.id = :moveId')
            ->setParameter('moveId', $pokemonMove->getMove()->getId())
            ->getQuery()
            ->getOneOrNullResult();
        ;
        return str_replace('’', '\'', $moveName->getName());
    }

    /*
    public function findOneBySomeField($value): ?MoveName
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
