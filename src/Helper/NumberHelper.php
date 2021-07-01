<?php

namespace App\Helper;

class NumberHelper
{
    public static function formatNumber(string $number){
        $number = trim($number);
        if(is_numeric($number)){
            return$number;
        }
        elseif ($number === '—') {
            return null;
        }
        elseif ($number === 'N/A') {
            return null;
        }
        elseif (preg_match('/\d\*/',$number)) {
            return (int)$number;
        } else {
            throw new \RuntimeException('Invalid number : ' . $number);
        }
    }
}