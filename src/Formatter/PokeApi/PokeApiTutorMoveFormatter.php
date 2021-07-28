<?php


namespace App\Formatter\PokeApi;


use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Formatter\MoveFullFiller;
use App\Helper\GenerationHelper;
use App\Helper\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
use App\Formatter\DTO;

class PokeApiTutorMoveFormatter
{
    private EntityManagerInterface $em;
    private MoveFullFiller $moveFullFiller;
    private GenerationHelper $generationHelper;

    public function __construct(
        EntityManagerInterface $em,
        MoveFullFiller $moveFullFiller,
        GenerationHelper $generationHelper
    )
    {
        $this->em = $em;
        $this->moveFullFiller = $moveFullFiller;
        $this->generationHelper = $generationHelper;
    }

    public function getFormattedLevelPokeApiMoves(
        Pokemon $pokemon,
        int $generation,
        MoveLearnMethod $learnMethod
    ): array
    {
        $preFormatteds = $this->getPreFormattedLevelPokeApiMoves($pokemon, $generation, $learnMethod);
        $formatteds = [];
        if (in_array($generation, [1, 2, 5, 6, 8])) {
            foreach ($preFormatteds as $name => $move) {
                $first = $this->formatLevel($move, 1, 0);
                $second = $this->formatLevel($move, 2, $first['weight']);
                $totalWeight = $this->calculateTotalWeight([$first, $second], $formatteds);
                $formatteds[strval($totalWeight)] =
                    strtr('%name% / %firstLevel% / %secondLevel%',
                        [
                            '%name%' => $name,
                            '%firstLevel%' => $first['level'],
                            '%secondLevel%' => $second['level'],
                        ]
                    );
            }
        } else {
            foreach ($preFormatteds as $name => $move) {
                $first = $this->formatLevel($move, 1, 0);
                $second = $this->formatLevel($move, 2, $first['weight']);
                $third = $this->formatLevel($move, 3, $second['weight']);
                $totalWeight = $this->calculateTotalWeight([$first, $second,$third],$formatteds);
                $formatteds[$totalWeight] = strtr('%name% / %firstLevel% / %secondLevel% / %thirdLevel%',
                    [
                        '%name%' => $name,
                        '%firstLevel%' => $first['level'],
                        '%secondLevel%' => $second['level'],
                        '%thirdLevel%' => $third['level'],
                    ]
                );
            }
        }
        ksort($formatteds);
        return $formatteds;
    }

    private function getPreFormattedLevelPokeApiMoves(
        Pokemon $pokemon,
        int $generation,
        MoveLearnMethod $learnMethod
    ): array
    {
        $preformatteds = [];
        $columns = in_array($generation, [3, 4, 7]) ? 3 : 2;

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

                $name = MoveSetHelper::getNameByGeneration($nameEntity,$generation);
                if (array_key_exists($name, $preformatteds)) {
                    $move = $preformatteds[$name];
                } else {
                    $move = new DTO\LevelUpMove();
                }

                $move = $this->moveFullFiller->fullFillTutorMove($move,
                    $column,
                    $name,
                    $pokemonMoveEntity);

                $preformatteds[$name] = $move;
            }
        }
        return $preformatteds;
    }

    private function formatLevel($move, $column, $previousWeight): array
    {
        $level = '';
        $weight = 0;

        if ($move->{'level' . $column} === null && $move->{'onEvolution' . $column} === null && $move->{'onStart' . $column} === null) {
            return [
                'level' => '—',
                'weight' => $previousWeight
            ];
        }

        if ($move->{'onStart' . $column}) {
            $level .= 'Départ';
            $weight = 0;
        }

        if ($move->{'onEvolution' . $column}) {
            empty($level) ? $level = 'Évolution' : $level .= ', ' . 'Évolution';
            $weight = 0;
        }

        if ($move->{'level' . $column}) {
            if(($move->{'onStart' . $column} || $move->{'onEvolution' . $column} || $move->{'level' . $column . 'Extra'})) {
                if (empty($level)) {
                    $level .= 'N.' . $move->{'level' . $column};
                    $weight = $move->{'level' . $column};
                } else {
                    $level .= ', N.' . $move->{'level' . $column};
                    $weight = $move->{'level' . $column};
                }
            } else {
                $level .= $move->{'level' . $column};
                $weight = $move->{'level' . $column};
            }
        }

        if ($move->{'level' . $column . 'Extra'}) {
            $level .= ', N.' . $move->{'level' . $column . 'Extra'};
            $weight =
                $move->{'level' . $column . 'Extra'} > $move->{'level' . $column} ? $move->{'level' . $column . 'Extra'} : $move->{'level' . $column};
        }

        return [
            'level' => $level,
            'weight' => max([$previousWeight, $weight])
        ];
    }

    private function calculateTotalWeight(array $weights, array $formatteds)
    {
        $total = 0;
        foreach ($weights as $weight) {
            if ($total === 0 || $weight['weight'] < $total) {
                $total = $weight['weight'];
            }
        }

        while (true) {
            if (array_key_exists((string)$total, $formatteds)) {
                $total += 0.1;
            } else {
                return (string)$total;
            }
        }
    }
}
