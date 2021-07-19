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

    public function findLGPEPokemons()
    {
        $gen1 =  $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy','s')
            ->andWhere('p.pokemonIdentifier >= 1 AND p.pokemonIdentifier <= 151')
            ->andWhere('p.toImport = TRUE')
            ->orderBy('s.pokemonSpeciesOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $news = $this->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy','s')
            ->andWhere('p.name = \'melmetal\'')
            ->orWhere('p.name = \'meltan\'')
            ->andWhere('p.toImport = \'TRUE\'')
            ->orderBy('s.pokemonSpeciesOrder', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        return array_merge($gen1,$news);
    }

    public function findOneByBPIndex(string $index): ?Pokemon
    {
        $galar = false;
        $alola = false;

        if(preg_match('/[0-9]{3,3}G/',$index)) {
            $galar = true;
        } elseif (preg_match('/[0-9]{3,3}A/',$index)) {
            $alola = true;
        }

        $pokemonIdentifier = (int)$index;

        /** @var Pokemon $pokemon */
        $pokemon = $this->createQueryBuilder('p')
            ->andWhere('p.pokemonIdentifier = :identifier')
            ->setParameter('identifier', $pokemonIdentifier)
            ->getQuery()
            ->getOneOrNullResult();

        if ($galar) {
            return $this->createQueryBuilder('p')
                ->andWhere('p.name = :name')
                ->setParameter('name', $pokemon->getName() .'-galar')
                ->getQuery()
                ->getOneOrNullResult();
        }

        if($alola) {
            return $this->createQueryBuilder('p')
                ->andWhere('p.name = :name')
                ->setParameter('name', $pokemon->getName() .'-alola')
                ->getQuery()
                ->getOneOrNullResult();
        }

        return $pokemon;
    }
}
