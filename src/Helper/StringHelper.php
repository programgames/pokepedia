<?php

declare(strict_types=1);

namespace App\Helper;

class StringHelper
{
    public static function clearBraces(string $string): string
    {
        return str_replace(array('{', '}'), '', $string);
    }
}