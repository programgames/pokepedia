<?php


namespace App\Formatter\DTO;


class LevelUpMove
{
    public string $name;
    public int $level;
    public int $column;
    public bool $onEvolution;
    public bool $onStart;
}