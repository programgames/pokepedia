<?php


namespace App\Helper;


use App\Entity\Pokemon;
use App\Entity\PokemonFormName;
use App\Entity\SpecyName;
use App\Exception\PokemonNotFound;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

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

    public function getPokepediaPokemonUrlName(Pokemon $pokemon)
    {
        $specific = $name = $this->getPokepediaSpecificNameIfAvailable($pokemon);
        if($specific) {
            return $specific;
        }

        $specyName = $this->em->getRepository(SpecyName::class)
            ->findOneBy(
                [
                    'pokemonSpecy' => $pokemon->getPokemonSpecy(),
                    'language' => 5
                ]
            );
        if (!$specyName) {
            throw new RuntimeException(sprintf('SpecyName not found for pokemon:  %s', $pokemon->getName()));
        }
        if (false !== strpos($pokemon->getName(), "alola")) {
            $name = strtr(
                '%specyName%_%markup%',
                [
                    '%specyName%' => $specyName->getName(),
                    '%markup%' => 'd\'Alola'
                ]
            );
        } elseif (false !== strpos($pokemon->getName(), "galar")) {
            $name = strtr(
                '%specyName%_%markup%',
                [
                    '%specyName%' => $specyName->getName(),
                    '%markup%' => 'de_Galar'
                ]
            );
        } else {
            $name = $specyName->getName();
        }

        return str_replace(' ', '_', $name);
    }

    private function getPokepediaSpecificNameIfAvailable(Pokemon $pokemon)
    {
        $pokemonName =  $pokemon->getName();

        switch ($pokemonName) {
            case 'kyurem-black':
                return 'Kyurem_Noir';

            case 'kyurem-white':
                return 'Kyurem_Blanc';

            case 'necrozma-dusk':
                return 'Necrozma_Crinière_du_Couchant';

            case 'necrozma-dawn':
                return 'Necrozma_Ailes_de_l\'Aurore';

            case 'necrozma-ultra':
                return 'Ultra-Necrozma';

        }
        return null;
    }
}