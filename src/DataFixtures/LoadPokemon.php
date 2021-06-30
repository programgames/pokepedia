<?php


namespace App\DataFixtures;


use App\Entity\Pokemon;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadPokemon extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($pokemondId = 1 ; $pokemondId < 899;$pokemondId++) {

            if($pokemondId >= 1 && $pokemondId <= 151) {
                $generation = 1;
            } elseif ($pokemondId > 151 && $pokemondId <= 251) {
                $generation = 2;
            } elseif ($pokemondId > 252 && $pokemondId <= 386) {
                $generation = 3;
            } elseif ($pokemondId > 386 && $pokemondId <= 493) {
                $generation = 4;
            } elseif ($pokemondId > 493 && $pokemondId <= 649) {
                $generation = 5;
            } elseif ($pokemondId > 649 && $pokemondId <= 721) {
                $generation = 6;
            } elseif ($pokemondId > 721 && $pokemondId <= 809) {
                $generation = 7;
            } elseif ($pokemondId > 809 && $pokemondId <= 898) {
                $generation = 8;
            }  else {
                $generation = 0;
            }

            $pokemon = new Pokemon();
            $pokemon->setPokemonIdentifier($pokemondId);
            $pokemon->setGeneration($generation);
            $manager->persist($pokemon);
        }
        $manager->flush();
    }
}