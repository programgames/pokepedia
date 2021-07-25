<?php


namespace App\Comparator;


use RuntimeException;

class LevelMoveComparator
{
    /**
     * @throws RuntimeException
     */
    public function levelMoveComparator(array $pokepediaMoves, array $pokeApiMoves): bool
    {
        $pokepediaMoves = array_map(function ($item) {
            return str_replace(['N.',', ','<br>'], ['',' ',' '], $item);
        }, $pokepediaMoves);

        $pokeApiMoves = array_map(function ($item) {
            return str_replace(['N.',', ','<br>'], ['',' ',' '], $item);
        }, $pokeApiMoves);

        $diff = count($pokeApiMoves) !== count($pokepediaMoves);
        if ($diff) {
            throw new RuntimeException('Different move number');
        }

        foreach ($pokeApiMoves as $pokeApiMove) {
            if (!in_array($pokeApiMove, $pokepediaMoves, true)) {
                throw new RuntimeException(sprintf('Different move %s', $pokeApiMove));
            }
        }

        return true;
    }
}