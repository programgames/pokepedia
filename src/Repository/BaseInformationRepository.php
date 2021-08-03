<?php

namespace App\Repository;

use App\Entity\BaseInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseInformation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseInformation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseInformation[]    findAll()
 * @method BaseInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseInformation::class);
    }
}
