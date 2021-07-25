<?php


namespace App\Satanizer;


class PokepediaBaseInformationSatanizer
{
    public function extractType1(array $infos)
    {
        $family = preg_grep('/\| type1=/', $infos);
        $family = array_shift($family);
        return str_replace('| type1=','',$family);
    }
}