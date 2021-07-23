<?php

declare(strict_types=1);

namespace App\Helper;

class StringHelper
{
    public static function clearBracesAndBrs(string $string): string
    {
        $formated = preg_replace('/^{{|}}$/', '', $string, 2);
        $formated = preg_replace('/<br>/', '', $formated);
        return $formated;
    }
}
