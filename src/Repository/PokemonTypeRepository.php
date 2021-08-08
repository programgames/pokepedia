<?php

namespace App\Repository;

use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\PokemonType;
use App\Entity\PokemonTypePast;
use App\Entity\TypeName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PokemonType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PokemonType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PokemonType[]    findAll()
 * @method PokemonType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PokemonTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PokemonType::class);
    }

    public function getFrenchSlot1NameByGeneration(Pokemon $pokemon, int $gen)
    {
        /** @var Generation $generation */
        $generation = $this->getEntityManager()->getRepository(Generation::class)
            ->createQueryBuilder('g')
            ->andWhere('g.generationIdentifier = :gen')
            ->setParameter('gen', $gen)
            ->getQuery()
            ->getOneOrNullResult();

        /** @var PokemonTypePast $typePast */
        $typePast = $this->getEntityManager()->getRepository(PokemonTypePast::class)
            ->createQueryBuilder('tp')
            ->leftJoin('tp.pokemon', 'p')
            ->leftJoin('tp.generation', 'g')
            ->andWhere('p.id = :pid')
            ->andWhere('g.generationIdentifier >= :gid')
            ->andWhere('tp.slot = 1')
            ->setParameter('gid', $generation->getGenerationIdentifier())
            ->setParameter('pid', $pokemon->getId())
            ->getQuery()
            ->getOneOrNullResult();

        if ($typePast) {
            /** @var TypeName $typeName */
            $typeName = $this->getEntityManager()->getRepository(TypeName::class)
                ->createQueryBuilder('tn')
                ->leftJoin('tn.type', 'type')
                ->andWhere('type.id = :tid')
                ->andWhere('tn.language = 5')
                ->setParameter('tid', $typePast->getType()->getId())
                ->getQuery()
                ->getOneOrNullResult();

            return $typeName->getName();
        }

        $type1 = $this
            ->createQueryBuilder('pt')
            ->leftJoin('pt.pokemon', 'pokemon')
            ->andWhere('pt.slot = 1')
            ->andWhere('pokemon.id = :pid')
            ->setParameter('pid',$pokemon->getId())
            ->getQuery()
            ->getOneOrNullResult();

        $typeName = $this->getEntityManager()->getRepository(TypeName::class)
            ->createQueryBuilder('tn')
            ->leftJoin('tn.type', 'type')
            ->andWhere('type.id = :tid')
            ->andWhere('tn.language = 5')
            ->setParameter('tid', $type1->getType()->getId())
            ->getQuery()
            ->getOneOrNullResult();


        return $typeName->getName();
    }
}
