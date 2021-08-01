<?php


namespace App\Satanizer;

use App\Exception\WrongHeaderException;
use App\Exception\WrongLearnMoveFormat;

// Extract level moves from
class PokepediaLevelMoveSatanizer
{
    public function checkAndSanitizeMoves(array $moves): array
    {
        $comments = [];

        if (!in_array($moves[0], [
            '=== Par montée en [[niveau]] ===',
            '==== [[Septième génération]] ====',
            '==== [[Huitième génération]] ===='
        ])) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        }

        $comments[] = $moves[0];
        unset($moves[0]);
        foreach ($moves as $key => $move) {
            if (empty($move) || $move === '}}') {
                unset($moves[$key]);
            }
        }
        $comments =  array_merge($comments, $this->clearCommentaries($moves));

        if (!preg_match('/{{#invoke:Apprentissage|niveau/', reset($moves))) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', reset($moves)));
        }
        array_shift($moves);

        foreach ($moves as $move) {
            if (!preg_match('/.* \/ .* \/ .*/', reset($moves))) {
                throw new WrongLearnMoveFormat(sprintf('Invalid learn move: %s', $move));
            }
        }

        return [
            'comments' => $comments,
            'moves' => $moves
        ];
    }

    private function clearCommentaries(array &$moves)
    {
        $commentaries = [];

        if (empty(preg_grep('/#invoke/', $moves))) {
            throw new \Exception('temporary');
            return;
        }

        foreach ($moves as $key => $move) {
            if (!preg_match('/#invoke/', $move)) {
                $commentaries[] = $moves[$key];

                unset($moves[$key]);
            } else {
                return $commentaries;
            }
        }

        return $commentaries;

    }
}
