<?php


namespace App\Helper;

use App\Entity\Generation;
use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

class GenerationHelper
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function genGenerationNumberByName($generation): int
    {
        $mapping = [
            'generation-i' => 1,
            'generation-ii' => 2,
            'generation-iii' => 3,
            'generation-iv' => 4,
            'generation-v' => 5,
            'generation-vi' => 6,
            'generation-vii' => 7,
            'generation-viii' => 8,
        ];

        return $mapping[$generation];
    }

    public function getVersionGroupByGenerationAndColumn(int $generation, int $column): VersionGroup
    {
        $col1 = [
            '1' => 'red-blue',
            '2' => 'gold-silver',
            '3' => 'ruby-sapphire',
            '4' => 'diamond-pearl',
            '5' => 'black-white',
            '6' => 'x-y',
            '7' => 'sun-moon',
            '8' => 'sword-shield'
        ];
        $col2 = [
            '1' => 'yellow',
            '2' => 'crystal',
            '3' => 'emerald',
            '4' => 'platinum',
            '5' => 'black-2-white-2',
            '6' => 'omega-ruby-alpha-sapphire',
            '7' => 'ultra-sun-ultra-moon'
        ];

        $col3 = [
            '3' => 'firered-leafgreen',
            '4' => 'heartgold-soulsilver',
            '7' => 'lets-go'
        ];

        switch ($column) {
            case 1:
                $mapping = $col1;
                break;
            case 2:
                $mapping = $col2;
                break;
            case 3:
                $mapping = $col3;
                break;
        }

        return $this->em->getRepository(VersionGroup::class)->findOneBy(
            [
                'name' => $mapping[$generation]
            ]
        );
    }

    public static function convertGenerationToBulbapediaRomanNotation($number): string
    {
        $mapping = [
        '1' => 'I',
        '2' => 'II',
        '3' => 'III',
        '4' => 'IV',
        '5' => 'V',
        '6' => 'VI',
        '7' => 'VII',
        '8' => 'VIII',
    ];

        return $mapping[(string)$number];
    }

    public function isPokemonAvailableInGeneration(Pokemon $pokemon, Generation $generation)
    {
        $availabilityRepository = $this->em->getRepository(PokemonAvailability::class);

        $available = false;
        $gen = $generation->getGenerationIdentifier();
        switch ($gen) {
            case 1:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups(
                    $pokemon,
                    ['red-blue', 'yellow']
                );
                break;
            case 2:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups(
                    $pokemon,
                    ['gold-silver', 'crystal']
                );
                break;
            case 3:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups(
                    $pokemon,
                    ['ruby-sapphire', 'emerald', 'firered-leafgreen']
                );
                break;
            case 4:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups(
                    $pokemon,
                    ['diamond-pearl', 'platinum', 'heartgold-soulsilver']
                );
                break;
            case 5:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups(
                    $pokemon,
                    ['black-white', 'black-2-white-2']
                );
                break;
            case 6:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups(
                    $pokemon,
                    ['x-y', 'omega-ruby-alpha-sapphire']
                );
                break;
            case 7:
                $available = $availabilityRepository->isPokemonAvailableInVersionGroups(
                    $pokemon,
                    ['sun-moon', 'ultra-sun-ultra-moon', 'lets-go']
                );
                break;

        }
        return !empty($available);
    }
}
