<?php


namespace App\Cache;


use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;

class CacheHandler
{
    private PdoAdapter $cache;
    private EntityManagerInterface $em;

    public function __construct(Connection $connection,EntityManagerInterface $em)
    {
        $this->cache = new PdoAdapter($connection);
        $this->em = $em;
    }

    public function deletePokepediaPokemonMoveCache()
    {

        $this->cache->clear();

        //TODO specific cache delete

        return;
//        $pokemons = $this->em->getRepository(Pokemon::class)->findDefaultAndAlolaPokemons(1);
//        $learnmethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);
//        $generations = $this->em->getRepository(Generation::class)->findAll();
//
//        $pokemonIds = array_map(function ($value) {
//            return $value->getId();
//        }, $pokemons);
//
//        $generationsIds = array_map(function ($value) {
//            return $value->getId();
//        }, $generations);
//
//
//        foreach ($pokemonIds) {
//            foreach ($generationsIds) {
//                $this->cache->deleteItem()
//            }
//        }
//        $this->cache->deleteItems();
    }
}