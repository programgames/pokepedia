<?php


namespace App\Helper;


class GenerationHelper
{
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
}