<?php


namespace App\Helper;


use App\Entity\Pokemon;
use App\Entity\PokemonFormName;
use App\Entity\SpecyName;
use App\Exception\PokemonNotFound;
use Doctrine\ORM\EntityManagerInterface;

class PokemonHelper
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function findPokemonByFormName(Pokemon $pokemon, string $form, int $language)
    {
        //todo improve quote gestion
        if ($language === 9) {
            $formNameSpecy = str_replace('\'', '’', $form);
        } elseif($language === 5 ) {
            $formNameSpecy = str_replace( '’','\'', $form);
        } else {
            $formNameSpecy =$form;
        }
        $specyName = ($this->em->getRepository(SpecyName::class)
            ->findOneBy(
                [
                    'language' => $language,
                    'pokemonSpecy' => $pokemon->getPokemonSpecy()
                ]
            ))->getName();
        if ($specyName === $formNameSpecy) {
            return $pokemon;
        }
        $formName = $this->em->getRepository(PokemonFormName::class)
            ->findOneBy(['pokemonName' => $form]);
        if ($formName) {
            return $formName->getPokemonForm()->getPokemon();
        }

        $name = $this->em->getRepository(PokemonFormName::class)
            ->findOneBy(['name' => $form]);

        if ($name) {
            return $name->getPokemonForm()->getPokemon();
        }

        throw new PokemonNotFound(sprintf('Pokemon %s not found', $form));
    }

}