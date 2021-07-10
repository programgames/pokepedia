<?php


namespace App\Comparator;


class LevelMoveComparator
{
    public function levelMoveComparator(array $pokepediaMoves, array $pokeApiMoves)
    {

        $numberOfMoves = count($pokeApiMoves);
        for($i = 0;$i < $numberOfMoves;$i++) {
            if($pokepediaMoves[$i] !== $pokeApiMoves[$i]) {
                if (($i > 1 && $pokepediaMoves[$i] !== $pokeApiMoves[$i-1]) && ($i < $numberOfMoves && $pokepediaMoves[$i] !== $pokeApiMoves[$i+1]) ) {
                    throw new \RuntimeException(sprintf('Different move %s ---- %s',$pokepediaMoves[$i],$pokeApiMoves[$i]));
                }
            }
        }

        return true;
    }
}