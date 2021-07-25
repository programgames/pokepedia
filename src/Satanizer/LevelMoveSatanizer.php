<?php


namespace App\Satanizer;


use App\Exception\WrongHeaderException;
use App\Exception\WrongLearnMoveFormat;

class LevelMoveSatanizer
{
    public function checkAndSanitizeMoves(array $moves): array
    {
        if (!in_array($moves[0],['=== Par montée en [[niveau]] ===','==== [[Septième génération]] ====','==== [[Huitième génération]] ===='])) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        }

        unset($moves[0]);
        foreach ($moves as $key => $move) {
            if (empty($move) || $move === '}}') {
                unset($moves[$key]);
            }
        }

        if (!preg_match('/{{#invoke:Apprentissage|niveau/', reset($moves))) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', reset($moves)));
        }
        array_shift($moves);

        foreach ($moves as $move) {
            if (!preg_match('/.* \/ .* \/ .*/', reset($moves))) {
                throw new WrongLearnMoveFormat(sprintf('Invalid learn move: %s', $move));
            }
        }
        return $moves;
    }
}