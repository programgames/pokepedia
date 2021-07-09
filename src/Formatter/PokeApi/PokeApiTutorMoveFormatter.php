<?php


namespace App\Formatter\PokeApi;


use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use App\Formatter\MoveFullFiller;
use App\Helper\GenerationHelper;
use Doctrine\ORM\EntityManagerInterface;
use App\Formatter\DTO;

class PokeApiTutorMoveFormatter
{
    private EntityManagerInterface $em;
    private MoveFullFiller $moveFullFiller;
    private GenerationHelper $generationHelper;

    public function __construct(EntityManagerInterface $em, MoveFullFiller $moveFullFiller, GenerationHelper $generationHelper)
    {
        $this->em = $em;
        $this->moveFullFiller = $moveFullFiller;
        $this->generationHelper = $generationHelper;
    }

    public function getFormattedTutorPokeApiMoves(Pokemon $pokemon, int $generation, MoveLearnMethod $learnMethod): array
    {
        $preFormatteds = $this->getPreFormattedTutorPokeApiMoves($pokemon, $generation, $learnMethod);
        $formatteds = [];

        return $formatteds;

    }

    private function getPreFormattedTutorPokeApiMoves(Pokemon $pokemon, int $generation, MoveLearnMethod $learnMethod): array
    {
        $moves = [];
        $columns = in_array($generation, [3, 4]) ? 3 : 2;

        for ($column = 1; $column < $columns; $column++) {
            $moves = $this->em->getRepository(PokemonMove::class)
                ->findMovesByPokemonLearnMethodAndVersionGroup(
                    $pokemon,
                    $learnMethod,
                    $this->generationHelper->getVersionGroupByGenerationAndColumn($generation, $column)
                );

            $movesDTO = [];
            foreach ($moves as $pokemonMoveEntity) {
                $name = $this->em->getRepository(MoveName::class)
                    ->findAndFormatMoveNameByPokemonMove($pokemonMoveEntity);

                if (array_key_exists($name, $movesDTO)) {
                    $move = $movesDTO[$name];
                } else {
                    $move = new DTO\LevelUpMove();
                }

                $move = $this->moveFullFiller->fullFillTutorMove($move, $column, $name, $pokemonMoveEntity);

                $movesDTO[$name] = $move;
            }
            $moves[$column] = $movesDTO;
        }
        return $moves;
    }
}