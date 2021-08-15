<?php

namespace App\Repository;

use App\Entity\Generation;
use App\Entity\VersionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VersionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method VersionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method VersionGroup[]    findAll()
 * @method VersionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VersionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VersionGroup::class);
    }

    public function findHighestVersionGroupByGeneration(Generation $generation)
    {
        $versionGroups = $this->createQueryBuilder('vgs')
            ->leftJoin('vgs.generation','generation')
            ->andWhere('generation.id = :genId')
            ->andWhere('vgs.name != \'lets-go\'')
            ->setParameter('genId',$generation->getId())
            ->getQuery()
            ->getResult();

        if(count($versionGroups) === 1) {
            return reset($versionGroups);
        }

       $versionGroup = array_reduce($versionGroups, function($a, $b){
            return $a ? ($a->getVersionGroupOrder() > $b->getVersionGroupOrder() ? $a : $b) : $b;
        });

        return $versionGroup;
    }
}
