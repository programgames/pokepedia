<?php

namespace App\Formatter;

class MoveFullFiller
{
    public function fullFillTutorMove(DTO\LevelUpMove $move,int $column, $name, $pokemonMoveEntity): DTO\LevelUpMove
    {
        $move->{'name'.$column} = $name;
        $level = $pokemonMoveEntity->getLevel();
        if ($level === 1) {
            $move->{'onStart'.$column} = true;
        } elseif ($level === 0) {
            $move->{'onEvolution'.$column} = true;
        } else {
            $move->{'level'.$column} = $level;
        }

        return $move;
    }
}