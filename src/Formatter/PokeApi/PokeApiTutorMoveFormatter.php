<?php


namespace App\Formatter\PokeApi;


use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;
use App\Formatter\DTO;

class PokeApiTutorMoveFormatter
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getTutorPokeApiMoves(Pokemon $pokemon, int $generation, MoveLearnMethod $learnMethod): array
    {
        $moves1 = $this->em->getRepository(PokemonMove::class)
            ->findMovesByPokemonLearnMethodAndVersionGroup(
                $pokemon,
                $learnMethod,
                $this->getFirstVersionGroupByGeneration($generation)
            );

        $moves2 = $this->em->getRepository(PokemonMove::class)
            ->findMovesByPokemonLearnMethodAndVersionGroup(
                $pokemon,
                $learnMethod,
                $this->getSecondVersionGroupByGeneration($generation)
            );

        $moves1DTO = [];
        foreach ($moves1 as $pokemonMoveEntity) {
            $name = $this->em->getRepository(MoveName::class)
                ->findAndFormatMoveNameByPokemonMove($pokemonMoveEntity);

            if (array_key_exists($name, $moves1DTO)) {
                $move = $moves1DTO[$name];
            } else {
                $move = new DTO\LevelUpMove();
            }

            $move->name = $name;
            $level = $pokemonMoveEntity->getMove()->getLevel();
            if ($level === 1) {
                $move->onStart = true;
            } elseif ($level === 0) {
                $move->onEvolution = true;
            } else {
                $move->level = $level;
            }

            $move->level = $pokemonMoveEntity->getMove()->getLevel();
            $move->column = 1;

            $moves1DTO[$name] = $move;
        }

        $moves2DTO = [];
        foreach ($moves2 as $pokemonMoveEntity) {
            $name = $this->em->getRepository(MoveName::class)
                ->findAndFormatMoveNameByPokemonMove($pokemonMoveEntity);

            if (array_key_exists($name, $moves1DTO)) {
                $move = $moves2DTO[$name];
            } else {
                $move = new DTO\LevelUpMove();
            }

            $move = $this->fillMove($move, $name, $pokemonMoveEntity);
            $move->column = 2;

            $moves2DTO[$name] = $move;
        }

        return [
            '1' => $moves1DTO,
            '2' => $moves2DTO
        ];
    }

    private function getFirstVersionGroupByGeneration(int $generation): VersionGroup
    {
        $mapping = [
            '1' => 'red-blue',
            '4' => ''
        ];

        return $this->em->getRepository(VersionGroup::class)->findOneBy(
            [
                'name' => $mapping[$generation]
            ]
        );
    }

    private function getSecondVersionGroupByGeneration(int $generation): VersionGroup
    {
        $mapping = [
            '1' => 'yellow',
        ];

        return $this->em->getRepository(VersionGroup::class)->findOneBy(
            [
                'name' => $mapping[$generation]
            ]
        );
    }

    private function fillMove(DTO\LevelUpMove $move, $name, $pokemonMoveEntity)
    {
        $move->name = $name;
        $level = $pokemonMoveEntity->getMove()->getLevel();
        if ($level === 1) {
            $move->onStart = true;
        } elseif ($level === 0) {
            $move->onEvolution = true;
        } else {
            $move->level = $level;
        }

        $move->level = $pokemonMoveEntity->getMove()->getLevel();

        return $move;
    }
}