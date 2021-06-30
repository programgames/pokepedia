<?php


namespace App\Helper;


class MoveSetHelper
{
    /* console argument */
    public const TUTORING_TYPE = 'tutoring';
    public const LEVELING_UP_TYPE = 'leveling_up';

    /* bulbapedia title of sections*/
    public const BULBAPEDIA_TUTORING_TYPE_LABEL = 'By tutoring';
    public const BULBAPEDIA_LEVELING_UP_TYPE_LABEL = 'By leveling up';

    /* determine type of move for whole generation of move specific by game */
    public const MOVE_TYPE_SPECIFIC = 'specific';
    public const MOVE_TYPE_GLOBAL = 'global';

    /* used to parse type of learnlist in learnlist/tutorf for example */
    public const BULBAPEDIA_TUTOR_WIKI_TYPE = 'tutor';
    public const BULBAPEDIA_LEVEL_WIKI_TYPE = 'level';
}