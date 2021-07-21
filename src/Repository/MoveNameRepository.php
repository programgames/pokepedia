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

    public function findFrenchMoveNameByPokemonMove(PokemonMove $pokemonMove)
    {
        $moveName =  $this->createQueryBuilder('move_name')
            ->leftJoin('move_name.move','move' )
            ->andWhere('move_name.language = 5')
            ->andWhere('move.id = :moveId')
            ->setParameter('moveId', $pokemonMove->getMove()->getId())
            ->getQuery()
            ->getOneOrNullResult();
        ;

        return $moveName;
    }

    public function findEnglishMoveNameByName(string $name, int $generation): MoveName
    {
        $moveName =  $this->createQueryBuilder('move_name')
            ->where('move_name.language = 9')
            ->andWhere('move_name.name = :name')
            ->orWhere(sprintf('move_name.gen%s = :name',$generation))
            ->setParameter('name',$name)
            ->getQuery()
            ->getOneOrNullResult();
        if(!$moveName) {
            $name = str_replace('\'','’',$name);
            $moveName =  $this->createQueryBuilder('move_name')
                ->where('move_name.language = 9')
                ->andWhere('move_name.name = :name')
                ->orWhere(sprintf('move_name.gen%s = :name',$generation))
                ->setParameter('name',$name)
                ->getQuery()
                ->getOneOrNullResult();
        }
        if(!$moveName) {
            throw new \RuntimeException(sprintf('Missing moveName : %s',$name));
        }
        return $moveName;
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
