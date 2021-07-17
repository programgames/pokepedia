<?php


namespace App\Satanizer;


class BulbapediaMachineSatanizer
{
    public function getMoveNameByItem(array $machineInformations,int $generation) : string
    {
        $matches = preg_grep("/\|move$generation.*/",$machineInformations);
        $firstMatch = reset($matches);
        return substr($firstMatch, strpos($firstMatch, "=") + 1);
    }
}