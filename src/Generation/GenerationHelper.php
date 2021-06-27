<?php


namespace App\Generation;


class GenerationHelper
{
    public const MAPPING = [
        '1' => 'I',
        '2' => 'II',
        '3' => 'III',
        '4' => 'IV',
        '5' => 'V',
        '6' => 'VI',
        '7' => 'VII',
        '8' => 'VIII',
    ];

    public static function genNumberToLitteral($number)
    {
        return self::MAPPING[(string)$number];
    }
}