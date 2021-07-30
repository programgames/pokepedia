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

        foreach ($pokeApiMoves as $pokeApiMove) {
            if (!in_array($pokeApiMove, $pokepediaMoves, true)) {
                return false;
            }
        }

        return true;
    }
}
