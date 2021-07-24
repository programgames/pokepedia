<?php


namespace App\Satanizer;


class BulbapediaAvailabilitySatanizer
{
    public function sanitizeAvailabilities(array $availabilitiesInformation) : array
    {
        return preg_grep("/Avail.*/",$availabilitiesInformation);
    }
}