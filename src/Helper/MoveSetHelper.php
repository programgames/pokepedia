<?php


namespace App\Helper;


use App\Entity\Pokemon;
use App\Entity\SpecyName;
use Doctrine\ORM\EntityManagerInterface;

class MoveSetHelper
{
    public const POKEPEDIA_LEVELING_UP_TYPE_LABEL = 'Par montée en niveau';
    public  const LEVELING_UP_TYPE = 'tutor';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getPokepediaPokemonName(Pokemon $pokemon)
    {
        $specyName = $this->em->getRepository(SpecyName::class)
            ->findOneBy(
                [
                    'pokemonSpecy' => $pokemon->getPokemonSpecy(),
                    'language' => 5
                ]
            );
        if(!$specyName) {
            throw new \RuntimeException(sprintf('SpecyName not found for pokemon:  %s',$pokemon->getName()));
        }
        if ($pokemon->getIsAlola()) {
            $name = strtr('%specyName%_%markup%',
                [
                    '%specyName%' => $specyName->getName(),
                    '%markup%' => 'd\'Alola'
                ]
            );
        } elseif ($pokemon->getIsGalar()) {
            $name = strtr('%specyName%_%markup%',
                [
                    '%specyName%' => $specyName->getName(),
                    '%markup%' => 'de_Galar'
                ]
            );
        } elseif ($pokemon->getSpecificName()) {
            $name = $pokemon->getSpecificName();
        } else {
            $name = $specyName->getName();
        }

        return $name;
    }


}