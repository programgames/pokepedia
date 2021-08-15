<?php

namespace App\Helper;

use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMoveAvailability;
use App\Entity\SpecyName;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

class MoveSetHelper
{
    public const LEVELING_UP_TYPE = 'level-up';
    public const MACHINE_TYPE = 'machine';
    public const EGG_TYPE = 'egg';
    public const TUTOR_TYPE = 'tutor';

    /* bulbapedia title of sections*/
    public const BULBAPEDIA_TUTORING_TYPE_LABEL = 'By tutoring';
    public const BULBAPEDIA_LEVELING_UP_TYPE_LABEL = 'By leveling up';
    public const BULBAPEDIA_BREEDING_TYPE_LABEL = 'By breeding';
    public const BULBAPEDIA_TMHM_TYPE_LABEL = 'By TM/HM';
    public const BULBAPEDIA_TM_TYPE_LABEL = 'By TM';
    public const BULBAPEDIA_TMTR_TYPE_LABEL = 'By TM/TR';

    /* used to parse type of learnlist in learnlist/tutorf for example */
    public const BULBAPEDIA_TUTOR_WIKI_TYPE = 'tutor';
    public const BULBAPEDIA_LEVEL_WIKI_TYPE = 'level';
    public const BULBAPEDIA_BREEDING_WIKI_TYPE = 'breed';
    public const BULBAPEDIA_MOVE_TYPE_GLOBAL = 'global';
    public const BULBAPEDIA_MOVE_TYPE_SPECIFIC = 'specific';
    public const BULBAPEDIA_TM_WIKI_TYPE = 'tm';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getNameByGeneration(MoveName $name, int $generation)
    {
        return $name->{'getGen' . $generation}() ?? $name->getName();
    }

    public static function convertLevel($level)
    {
        $number = trim($level);
        if (is_numeric($number)) {
            return $number;
        }

        if ($number === '{{tt||Evo.||Learned upon evolving}}') {
            return 0;
        }
        if ($number === '—') {
            return null;
        }

        if ($number === 'N/A') {
            return null;
        }

        if (preg_match('/\d\*/', $number)) {
            return (int)$number;
        }

        throw new RuntimeException('Invalid number : ' . $number);
    }

    public static function getBulbapediaMachineLabelByGeneration($generation): string
    {
        if ($generation < 7) {
            return self::BULBAPEDIA_TMHM_TYPE_LABEL;
        }

        if ($generation === 7) {
            return self::BULBAPEDIA_TM_TYPE_LABEL;
        }

        return self::BULBAPEDIA_TMTR_TYPE_LABEL;
    }

    public static function getPokepediaInvokeLearnMethod(MoveLearnMethod $learnMethod): string
    {
        $english = $learnMethod->getName();
        $french = null;
        switch ($english) {
            case 'level-up':
                $french = 'niveau';
                break;
        }
        if (!$french) {
            throw new RuntimeException(sprintf('Impossible to translate learn method %s to french', $english));
        }

        return $french;
    }
}
