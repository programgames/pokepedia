<?php


namespace App\Formatter\PokeApi;


use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
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
        if (empty($preFormatteds[3])) {
            $firstColumn = count($preFormatteds[1]);
            $secondColumn = count($preFormatteds[2]);
            $moves = $firstColumn > $secondColumn ? $preFormatteds[1] : $preFormatteds[2];

            foreach ($moves as $name => $move) {
                strtr('%name% / %firstLevel% / %secondLevel%',
                    [
                        '%name%' => $name,
                        '%firstLevel%' => $this->formatLevel(),
                    ]
                );
            }

        }

        return [];
    }

    private function getPreFormattedTutorPokeApiMoves(Pokemon $pokemon, int $generation, MoveLearnMethod $learnMethod): array
    {
        $preformatteds = [];
        $columns = in_array($generation, [3, 4]) ? 3 : 2;

        for ($column = 1; $column < $columns + 1; $column++) {
            $moves = $this->em->getRepository(PokemonMove::class)
                ->findMovesByPokemonLearnMethodAndVersionGroup(
                    $pokemon,
                    $learnMethod,
                    $this->generationHelper->getVersionGroupByGenerationAndColumn($generation, $column)
                );

            foreach ($moves as $pokemonMoveEntity) {
                $nameEntity = $this->em->getRepository(MoveName::class)
                    ->findMoveNameByPokemonMove($pokemonMoveEntity);

                if (array_key_exists($nameEntity->getName(), $preformatteds)) {
                    $move = $preformatteds[$nameEntity->getName()];
                } else {
                    $move = new DTO\LevelUpMove();
                }

                $move = $this->moveFullFiller->fullFillTutorMove($move, $column, $nameEntity->getName(), $pokemonMoveEntity);

                $preformatteds[$nameEntity->getName()] = $move;
            }
        }
        return $preformatteds;
    }
}