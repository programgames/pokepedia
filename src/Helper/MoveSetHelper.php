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

    public static function getNameByGeneration(MoveName $name, int $generation)
    {
        return $name->{'getGen' . $generation}() ?? $name->getName();
    }

}
