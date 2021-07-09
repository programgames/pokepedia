<?php


namespace App\Helper;


use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

class GenerationHelper
{
    private EntityManagerInterface $em;

    public static function genGenerationNumberByName($generation)
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
            '2' => ''
        ];
        $col2 = [
            '1' => 'yellow',
        ];

        $col3 = [

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
}