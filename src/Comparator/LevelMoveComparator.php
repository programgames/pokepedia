<?php

namespace App\Comparator;

use RuntimeException;

/**
 * Compare level moves between database and pokepedia
 */
class LevelMoveComparator
{
    /**
     * @throws RuntimeException
     */
    public function levelMoveComparator(array $pokepediaMoves, array $pokeApiMoves): bool
    {
        $pokepediaMoves = array_map(function ($item) {
            return str_replace(['N.', ', ', '<br>'], ['', ' ', ' '], $item);
        }, $pokepediaMoves);

        $pokeApiMoves = array_map(function ($item) {
            return str_replace(['N.', ', ', '<br>'], ['', ' ', ' '], $item);
        }, $pokeApiMoves);

        $count = count($pokeApiMoves);
        if ($count!==  count($pokepediaMoves)) {
            return false;
        }
        for ($i = 0 ; $i < $count ; $i++) {
            if ($pokepediaMoves[$i] != $pokeApiMoves[$i]) {
                return false;
            }
        }

        return true;
    }
}
