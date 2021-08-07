<?php


namespace App\Helper;


use Doctrine\ORM\EntityManagerInterface;

class PokemonHelper
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


}