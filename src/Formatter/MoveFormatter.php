<?php

namespace App\Formatter;

use App\Exception\EmptyMoveSetException;

class MoveFormatter
{
    public function formatLevelingLearnlist(array $moves, int $generation)
    {
        foreach ($moves as $move) {

            if (preg_match('/level\dnull/', $move, $matches)) {
                throw new EmptyMoveSetException(sprintf('Empty moveset'));
            }

            if (preg_match('/level\d+.*/', $move, $matches)) {
                $formattedMoves[] = [
                    'format' => 'numeral',
                    'value' => explode('|', $move),
                    'gen' => $generation
                ];
            }
            if (preg_match('/level[XVI]+.*/', $move, $matches)) {
                $formattedMoves[] = [
                    'format' => 'roman',
                    'value' => explode('|', $move),
                    'gen' => $generation
                ];
            }

        }

        return $formattedMoves;
    }
}