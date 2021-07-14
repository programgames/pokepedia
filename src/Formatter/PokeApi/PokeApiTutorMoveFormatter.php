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
        $formatted = [];
        if (in_array($generation, [1, 2, 5, 6, 8])) {
            foreach ($preFormatteds as $name => $move) {
                $formatted[] = strtr('%name% / %firstLevel% / %secondLevel%',
                    [
                        '%name%' => $name,
                        '%firstLevel%' => $this->formatLevel($move, 1),
                        '%secondLevel%' => $this->formatLevel($move, 2),
                    ]
                );
            }

        } else {
            foreach ($preFormatteds as $name => $move) {
                $formatted[] = strtr('%name% / %firstLevel% / %secondLevel% / %thirdLevel%',
                    [
                        '%name%' => $name,
                        '%firstLevel%' => $this->formatLevel($move, 1),
                        '%secondLevel%' => $this->formatLevel($move, 2),
                        '%thirdLevel%' => $this->formatLevel($move, 3),
                    ]
                );
            }
        }

        return $formatted;
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
                    ->findFrenchMoveNameByPokemonMove($pokemonMoveEntity);

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

    private function formatLevel($move, $column): string
    {
        $level = '';

        if ($move->{'level' . $column} === null && $move->{'onEvolution' . $column} === null && $move->{'onStart' . $column} === null) {
            return '-';
        }

        if ($move->{'onStart' . $column}) {
            $level .= 'Départ';
        }

        if ($move->{'onEvolution' . $column}) {
            empty($level) ? $level = 'Évolution' : $level .= ', ' . 'Évolution';
        }

        if ($move->{'level' . $column}) {
            empty($level) ? $level = $move->{'level' . $column} : $level .= ', ' . $move->{'level' . $column};
        }

        if ($move->{'level' . $column . 'Extra'}) {
            $level .= ', ' . $move->{'level' . $column.'Extra'};
        }

        return $level;
    }
}