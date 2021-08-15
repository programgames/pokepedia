<?php


namespace App\Helper;


use App\Entity\Generation;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManager;

class VersionGroupHelper
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
}