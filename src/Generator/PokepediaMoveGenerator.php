<?php


namespace App\Generator;


use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;

class PokepediaMoveGenerator
{

    public function generateMoveWikiText(MoveLearnMethod $learnMethod,Pokemon $pokemon,int $gen,$moves): string
    {
        $generated = '';
//        if($learnMethod->getName() === 'level-up') {
//            $generated .= '=== Par montée en [[niveau]] ===' . $this->lb() . $this->lb();
//        }

        $generated .= sprintf("{{#invoke:Apprentissage|niveau|type=%s|génération=%s|". $this->lb(),
            $pokemon->getBaseInformation()->getType1(),
            $gen
        );

        foreach ($moves as $move){
            $generated .= $move . $this->lb();
        }
        $generated .= "}}";

        return $generated;
    }

    private function lb(): string
    {
        return "\r\n";
    }
}