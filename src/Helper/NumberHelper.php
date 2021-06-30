<?php

namespace App\Helper;

class NumberHelper
{
    public static function formatNumber(string $number){
        if(is_numeric($number)){
            return$number;
        }
        elseif ($number === '—') {
            return null;
        }
        elseif ($number === 'N/A') {
            return null;
        } else {
            throw new \RuntimeException('Invalid number : ' . $number);
        }
    }
}