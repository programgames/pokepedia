<?php

namespace App\Sanitize;

use App\Exception\WrongHeaderException;
use App\Exception\WrongLearnListFormat;

class MoveSatanizer
{
    public function checkAndSanitizeTutoringMoves(array $moves)
    {
        $movesSize = count($moves);
        if ($moves[0] !== '====By [[Move Tutor|tutoring]]====') {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        };

        if (!preg_match('/{{learnlist\/tutorh.*}}/', $moves[1], $matches)) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[1]));
        }

        for ($i = 2 ; $i < $movesSize - 1;$i++) {
            $moves[$i] = str_replace(array('{', '}'), '', $moves[$i]);

            if (!preg_match('/learnlist\/tutorl\d+.*/', $moves[$i], $matches)
                && !preg_match('/learnlist\/tutor\dnull/', $moves[$i], $matches)
                ) {
                throw new WrongLearnListFormat(sprintf('Invalid learnlist: %s', $moves[$i]));
            }
        }
        if (!preg_match('/{{learnlist\/tutorf.*}}/', $moves[$i] , $matches)) {
            throw new WrongHeaderException(sprintf('Invalid footer: %s', $moves[1]));
        }

        return $moves;
    }

    public function checkAndSanitizeLevelingMoves(array $moves)
    {
        $movesSize = count($moves);
        if ($moves[0] !== '====By [[Level|leveling up]]====') {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        };

        if (!preg_match('/{{learnlist\/levelh.*}}/', $moves[1], $matches)) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[1]));
        }

        for ($i = 2 ; $i < $movesSize - 1;$i++) {
            $moves[$i] = str_replace(array('{', '}'), '', $moves[$i]);

            if (!preg_match('/learnlist\/level\d+.*/', $moves[$i], $matches)
                && !preg_match('/learnlist\/level\dnull/', $moves[$i], $matches)
                && !preg_match('/learnlist\/level[XVI]+.*/', $moves[$i], $matches)) {
                throw new WrongLearnListFormat(sprintf('Invalid learnlist: %s', $moves[$i]));
            }
        }
        if (!preg_match('/{{learnlist\/levelf.*}}/', $moves[$i] , $matches)) {
            throw new WrongHeaderException(sprintf('Invalid footer: %s', $moves[1]));
        }

        return $moves;
    }
}