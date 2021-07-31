<?php

namespace App\Formatter;

/** Helper class */
class MoveFullFiller
{
    public function fullLevelingMove(DTO\LevelUpMove $move, int $column, $name, $pokemonMoveEntity): DTO\LevelUpMove
    {
        $move->{'name'.$column} = $name;
        $level = $pokemonMoveEntity->getLevel();
        if ($level === 1) {
            $move->{'onStart'.$column} = true;
        } elseif ($level === 0) {
            $move->{'onEvolution'.$column} = true;
        } else {
            if ($move->{'level'.$column} === null) {
                $move->{'level' . $column} = $level;
            } else {
                $move->{'level' . $column.'Extra'} = $level;
            }
        }

        return $move;
    }
}
