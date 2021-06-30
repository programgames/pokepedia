<?php

namespace App\Formatter;

use App\Exception\EmptyMoveSetException;

class MoveFormatter
{
    public function formatTutoringLearnlist(string $move, int $generation)
    {

        if (preg_match('/tutor\dnull/', $move, $matches)) {
            $return = [
                'format' => 'empty',
                'value' => null,
                'gen' => $generation
            ];
        }

        if (preg_match('/tutor\d+.*/', $move, $matches)) {
            $return = [
                'format' => 'numeral',
                'value' => explode('|', $move),
                'gen' => $generation
            ];
        }
    }

    public function formatLevelingLearnlist(string $move, int $generation)
    {
        if (preg_match('/level\dnull/', $move, $matches)) {
            $return = [
                'format' => 'empty',
                'value' => null,
                'gen' => $generation
            ];
        }

        if (preg_match('/level\d+.*/', $move, $matches)) {
            return [
                'format' => 'numeral',
                'value' => explode('|', $move),
                'gen' => $generation
            ];
        }
        if (preg_match('/level[XVI]+.*/', $move, $matches)) {
            return [
                'format' => 'roman',
                'value' => explode('|', $move),
                'gen' => $generation
            ];
        }
    }
}
