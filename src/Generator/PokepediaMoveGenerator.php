<?php

namespace App\Generator;

use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonType;
use App\Entity\Type;
use App\Helper\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;

/** Generate pokepedia wikitext for pokemon moves */
class PokepediaMoveGenerator
{
    public const CLI_MODE = 0;
    public const HTML_MODE = 1;

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
        $frenchSlot1NameByGeneration = $this->em->getRepository(PokemonType::class)
            ->getFrenchSlot1NameByGeneration($pokemon,$gen);
        $pokepediaLearnMethod = MoveSetHelper::getPokepediaInvokeLearnMethod($learnMethod);
        $generated .= sprintf(
            "{{#invoke:Apprentissage|%s|type=%s|génération=%s|" . $this->lb($mode),
            $pokepediaLearnMethod,
            $frenchSlot1NameByGeneration,
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
