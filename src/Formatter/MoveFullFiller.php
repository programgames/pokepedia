<?php


namespace App\Formatter;


use App\Entity\VersionGroup;

class MoveFullFiller
{
    public function fullFillTutorMove(DTO\LevelUpMove $move,int $column, $name, $pokemonMoveEntity)
    {
        $move->name = $name;
        $level = $pokemonMoveEntity->getMove()->getLevel();
        if ($level === 1) {
            $move->onStart = true;
        } elseif ($level === 0) {
            $move->onEvolution = true;
        } else {
            $move->level = $level;
        }

        $move->level = $pokemonMoveEntity->getMove()->getLevel();
        $move->column = $column;

        return $move;
    }
}