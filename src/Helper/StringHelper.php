<?php

declare(strict_types=1);

namespace App\Helper;

class StringHelper
{
    public static function clearBraces(string $string): string
    {
        $formated = preg_replace('/^{{|}}$/', '', $string, 2);
        return $formated;
    }
}