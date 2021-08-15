<?php

namespace App\Comparator;

/**
 * Compare level moves between database and pokepedia
 */
class LevelMoveComparator
{
    /**
     * @param array $pokepediaMoves
     * @param array $pokeApiMoves
     * @return array
     */
    public function levelMoveComparator(array $pokepediaMoves, array $pokeApiMoves): boolean
    {
        $pokepediaMoves = array_map(static function ($item) {
            return str_replace(['N.', ', ', '<br>','-'], ['', ' ', ' ','—'], $item);
        }, $pokepediaMoves);

        $pokeApiMoves = array_map(static function ($item) {
            return str_replace(['N.', ', ', '<br>','-'], ['', ' ', ' ','—'], $item);
        }, $pokeApiMoves);

        $count = count($pokeApiMoves);
        if ($count!==  count($pokepediaMoves)) {
            return false;
        }
        for($i = 0 ; $i < $count ; $i++) {
            if(!in_array($pokeApiMoves[$i], $pokepediaMoves, true) || !in_array($pokepediaMoves[$i], $pokeApiMoves, true)) {
                return false;

            }
        }

        for ($i = 0 ; $i < $count ; $i++) {
            if ($pokepediaMoves[$i] !== $pokeApiMoves[$i]) {
                return false;
            }
        }
        return false;
    }
}
