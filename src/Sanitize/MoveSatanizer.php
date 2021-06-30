<?php

namespace App\Sanitize;

use App\Exception\WrongFooterException;
use App\Exception\WrongLearnListFormat;
use App\Formatter\MoveFormatter;
use App\Formatter\StringHelper;

class MoveSatanizer
{
    private MoveFormatter $moveFormatter;

    public function __construct(MoveFormatter $moveFormatter)
    {
        $this->moveFormatter = $moveFormatter;
    }

    public function checkAndSanitizeTutoringMoves(array $moves)
    {
        $movesSize = count($moves);
        if ($moves[0] !== '====By [[Move Tutor|tutoring]]====') {
            throw new WrongFooterException(sprintf('Invalid header: %s', $moves[0]));
        };

        if (!preg_match('/learnlist\/tutorh.*/', $moves[1], $matches)) {
            throw new WrongFooterException(sprintf('Invalid header: %s', $moves[1]));
        }

        for ($i = 2; $i < $movesSize - 1; $i++) {
            $moves[$i] = StringHelper::clearBraces( $moves[$i]);

            if (!preg_match('/learnlist\/tutorl\d+.*/', $moves[$i], $matches)
                && !preg_match('/learnlist\/tutor\dnull/', $moves[$i], $matches)
            ) {
                throw new WrongLearnListFormat(sprintf('Invalid learnlist: %s', $moves[$i]));
            }
        }
        if (!preg_match('/learnlist\/tutorf.*/', $moves[$i], $matches)) {
            throw new WrongFooterException(sprintf('Invalid footer: %s', $moves[1]));
        }

        return $moves;
    }

    public function checkAndSanitizeLevelingMoves(array $moves, int $generation)
    {
        $movesByForms = [];

        $movesSize = count($moves);
        if ($moves[0] !== '====By [[Level|leveling up]]====') {
            throw new WrongFooterException(sprintf('Invalid header: %s', $moves[0]));
        };
        if (!preg_match('/=====.*=====/', $moves[1], $matches)) {
            return $this->handleFormMoves($moves);
        }

        if (!preg_match('/learnlist\/levelh.*/', $moves[1], $matches)) {
            throw new WrongFooterException(sprintf('Invalid header: %s', $moves[1]));
        }

        for ($i = 2; $i < $movesSize - 1; $i++) {
            $moves[$i] = StringHelper::clearBraces( $moves[$i]);

            if (!preg_match('/learnlist\/level\d+.*/', $moves[$i], $matches)
                && !preg_match('/learnlist\/level\dnull/', $moves[$i], $matches)
                && !preg_match('/learnlist\/level[XVI]+.*/', $moves[$i], $matches)) {
                throw new WrongLearnListFormat(sprintf('Invalid learnlist: %s', $moves[$i]));
            }
            //TODO a verifier
            $this->moveFormatter->formatLevelingLearnlist($moves[$i], $generation);
        }
        if (!preg_match('/learnlist\/levelf.*/', $moves[$i], $matches)) {
            throw new WrongFooterException(sprintf('Invalid footer: %s', $moves[1]));
        }

        $movesByForm['noform'] = $moves;

        return $moves;
    }

    private function handleFormMoves(array $moves): array
    {
        $movesByForms = [];
        $size = count($moves);
        $form = null;
        array_shift($moves);

        for ($i = 0; $i < $size; $i++) {
            if (empty($moves[$i])) {
                continue;
            }

            if (!$form && !preg_match('/=====.*=====/', $moves[$i], $matches)) {
                $form = str_replace('=', '', $moves[$i]);
                if (!preg_match('/learnlist\/levelh.*/', $moves[$i + 1], $matches)) {
                    throw new WrongFooterException(sprintf('Invalid header: %s', $moves[1]));
                }
                $i++;
                continue;
            }
            if ($form && (preg_match('/learnlist\/level\d+.*/', $moves[$i], $matches)
                    || preg_match('/learnlist\/level\dnull/', $moves[$i], $matches)
                    || preg_match('/learnlist\/level[XVI]+.*/', $moves[$i], $matches))) {
                $movesByForms[$form][] = $moves[$i];
            } elseif (preg_match('/learnlist\/levelf.*/', $moves[$i], $matches)) {
                $form = null;
            } else {
                throw new WrongFooterException(sprintf('Invalid footer: %s', $moves[1]));
            }
        }
        return $movesByForms;
    }
}
