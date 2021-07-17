<?php

namespace App\Formatter\Bulbapedia;


use App\Exception\WrongLearnListFormat;
use App\Helper\MoveSetHelper;

class MoveFormatter
{
    public function formatLearnlist(string $move, int $generation, string $type)
    {
        if (preg_match(sprintf('/%s\dnull/', $type), $move)) {
            return [
                'format' => 'empty',
                'value' => null,
                'gen' => $generation
            ];
        }

        if (preg_match(sprintf('/%s\d+.*/', $type), $move)) {
            return [
                'type' => $type,
                'format' => MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL,
                'value' => $this->explodeMove( $move),
                'gen' => $generation
            ];
        }
        if (preg_match(sprintf('/%s[XVI]+.*/', $type), $move)) {
            return [
                'type' => $type,
                'format' => 'roman',
                'value' => $this->explodeMove( $move),
                'gen' => $generation
            ];
        }

        throw new WrongLearnListFormat('Invalid learnlist: ' . $move);
    }

    private function explodeMove(string $move)
    {
        $move .= '|';
        $template = false;
        $moveData = [];
        $temp = "";
        $newWord = false;
        $length = strlen($move);

        for ($i = 0; $i < $length; $i++) {
            if ($move[$i] === "|") {
                if (isset($move[$i + 1]) && $move[$i + 1] === "{") {
                    $template = true;
                    $newWord = true;
                } elseif ($move[$i - 1] === "}" && $move[$i - 2] === "}") {
                    $template = false;
                }
                if($template) {
                    $temp .= $move[$i];
                } else {
                    $newWord = true;
                }
            }
            if($newWord) {
                $moveData[] = $temp;
                $temp = "";
                $newWord = false;
            } else {
                $temp .= $move[$i];
            }
        }

        return $moveData;
    }
}