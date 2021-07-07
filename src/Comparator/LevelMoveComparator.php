<?php


namespace App\Comparator;


class LevelMoveComparator
{

    public function levelMoveComparator(array $pokepediaMoves, array $pokeApiMoves)
    {
        if()
        $numberOfMoves = count($pokeApiMoves);
        for($i = 0;$i < $numberOfMoves;$i++) {
            if($pokepediaMoves[$i] !== $pokeApiMoves[$i]) {
                throw new \RuntimeException(sprintf('Different move %s ---- %s',$pokepediaMoves[$i],$pokeApiMoves[$i]));
            }
        }

        return true;
    }
}