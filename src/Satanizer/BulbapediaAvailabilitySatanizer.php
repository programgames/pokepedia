<?php


namespace App\Satanizer;


class BulbapediaAvailabilitySatanizer
{
    public function sanitizeAvailabilities(array $availabilitiesInformation) : array
    {
        $matches = preg_grep("/Avail.*/",$availabilitiesInformation);
        return $matches;
    }
}