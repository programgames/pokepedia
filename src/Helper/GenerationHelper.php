<?php


namespace App\Helper;


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
            case 1 :
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

}