<?php

namespace App\Satanizer;

use App\Exception\WrongHeaderException;
use RuntimeException;

// Extract level moves from
class PokepediaLevelMoveSatanizer
{
    public function checkAndSanitizeMoves(array $moves): array
    {
        $template = false;
        $end = false;
        $form = false;
        $actualForm = null;
        $section = [
            'topComments' => [],
            'forms' => [],
            'botComments' => [],
        ];

        if (!in_array($moves[0], [
            '=== Par montée en [[niveau]] ===',
            '==== Par montée en niveau ====',
            '===Par montée en [[niveau]] ===',
            '==== [[Septième génération]] ====',
            '==== [[Huitième génération]] ====',
        ])) {
            throw new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        }
        $section['topComments'][] = $moves[0];
        unset($moves[0]);
        $templates = count(preg_grep('/{{#invoke:Apprentissage\|niveau\|/', $moves));
        if ($templates === 0) {
            throw new RuntimeException('no level template found');
        }

        if ($templates === 1) {
            $forms['uniqForm'] = [
                'topComments' => [],
                'moves' => [],
                'botComments' => [],
            ];
            foreach ($moves as $key => $move) {
                if (!$template && !preg_match('/{{#invoke:Apprentissage\|niveau\|/', $move)) {
                    $section['topComments'][] = $move;
                } elseif (!$template && preg_match('/{{#invoke:Apprentissage\|niveau\|/', $move)) {
                    $template = true;
                } elseif ($template && preg_match('/}}/', $move)) {
                    $template = false;
                    $end = true;
                } elseif ($end) {
                    $section['botComments'][] = $move;
                } else {
                    $forms['uniqForm']['moves'][] = [];
                }
            }
            $section['forms'] = $forms;
            return $section;
        }


        foreach ($moves as $key => $move) {
            if (!$template && !$form && !preg_match('/=.*=/', $move)) {
                $section['topComments'][] = $move;
            }  elseif (!$template && $form && preg_match('/{{#invoke:Apprentissage\|niveau\|/', $move)) {
                $template = true;
            } elseif (!$template && preg_match('/=.*=/', $move)) {
                $form = true;
                $end = false;
                $actualForm = trim(str_replace('=','',trim($move)));
            } elseif ($template && preg_match('/}}/',$move)) {
                $template = false;
                $end = true;
            } elseif ($template && $form && !preg_match('/}}/',$move)) {
                $forms[$actualForm]['moves'][] = $move;
            } elseif ($form && !preg_match('/=.*=/', $move) && !$template) {
                $forms[$actualForm]['topComments'][] = $move;
            } elseif ($form && !$template && preg_match('/=.*=/', $move)) {
                $form = true;
                $actualForm = str_replace('=','',trim($move));
                $forms[$actualForm] = [
                    'topComments' => [],
                    'moves' => [],
                    'botComments' => [],
                ];
            } elseif ($end) {
                $forms[$actualForm]['botComments'][] = $move;
            }
        }
        $section['forms'] = $forms;


        return $section;
    }
}
