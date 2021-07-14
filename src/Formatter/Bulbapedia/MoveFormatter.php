<?php

namespace App\Formatter\Bulbapedia;


use App\Exception\WrongLearnListFormat;
use App\Helper\MoveSetHelper;

class MoveFormatter
{
    public function formatLearnlist(string $move, int $generation, string $type)
    {
        if (preg_match(sprintf('/%s\dnull/',$type), $move)) {
            $return = [
                'format' => 'empty',
                'value' => null,
                'gen' => $generation
            ];
        }

        if (preg_match(sprintf('/%s\d+.*/',$type), $move)) {
            return [
                'type' => $type,
                'format' => MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL,
                'value' => explode('|', $move),
                'gen' => $generation
            ];
        }
        if (preg_match(sprintf('/%s[XVI]+.*/',$type), $move)) {
            return [
                'type' => $type,
                'format' => 'roman',
                'value' => explode('|', $move),
                'gen' => $generation
            ];
        }

        throw new WrongLearnListFormat('Invalid learnlist: ' . $move);
    }
}