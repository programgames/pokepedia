<?php

namespace App\Generator;

use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;

/** Generate pokepedia wikitext for pokemon moves */
class PokepediaMoveGenerator
{
    public const CLI_MODE = 0;
    public const HTML_MODE = 1;

    public function generateMoveWikiText(MoveLearnMethod $learnMethod, Pokemon $pokemon, int $gen, $moves, array $commentaries, $mode = self::CLI_MODE): string
    {
        $generated = '';

        if (!empty($commentaries)) {
            foreach ($commentaries as $commentary) {
                if ((preg_match('/<br\/>/', $commentary) || preg_match('/<br>/', $commentary) || preg_match('/<\/br>/', $commentary)) && $mode === self::HTML_MODE) {
                    $generated .= $commentary;
                } else {
                    $generated .= $commentary . $this->lb($mode);
                }
            }
            $generated .= $this->lb($mode);
        }
        $pokepediaLearnMethod = MoveSetHelper::getPokepediaInvokeLearnMethod($learnMethod);
        $generated .= sprintf(
            "{{#invoke:Apprentissage|%s|type=%s|génération=%s|" . $this->lb($mode),
            $pokepediaLearnMethod,
            $pokemon->getBaseInformation()->getType1(),
            $gen
        );

        foreach ($moves as $move) {
            $generated .= $move . $this->lb($mode);
        }
        $generated .= "}}";

        return $generated;
    }

    private function lb($mode): string
    {
        if ($mode === self::CLI_MODE) {
            return "\r\n";
        }

        return '<br>';
    }
}
