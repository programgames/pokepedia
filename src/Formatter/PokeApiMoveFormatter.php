<?php


namespace App\Formatter;


use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

class PokeApiMoveFormatter
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getPokeApiMoves(Pokemon $pokemon, int $generation, MoveLearnMethod $learnMethod)
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

        if (count($moves1) !== count($moves2)) {
            throw new \RuntimeException(sprintf('Number of moves different between version for pokemon %s generation %s',
                $pokemon->getName(), $generation));
        }
        $numberOfMoves = count($moves1);
        $formattedMoves = [];

        $names = [];
        for ($i = 0; $i < $numberOfMoves; $i++) {
            if ($moves1[$i]->getMove()->getId() !== $moves2[$i]->getMove()->getId()) {
                throw new \RuntimeException(sprintf('Moves are different : %s %s', $moves1[$i]->getMove()->getId(), $moves2[$i]->getMove()->getId()));
            }
            $name = ($this->em->getRepository(MoveName::class)
                ->findOneBy(
                    [
                        'move' => $moves1[$i]->getMove(),
                        'language' => 5
                    ]
                ))->getName();
            $name = str_replace('’', '\'', $name);
            $names[] = $name;
            if (in_array($name, $names, true)) {
                continue;
            }
            $formattedMoves[] = sprintf('%s / %s / %s', $name, $this->formatLevel($moves1[$i]->getLevel()), $this->formatLevel($moves2[$i]->getLevel()));
        }

        return $formattedMoves;
    }

    private function getFirstVersionGroupByGeneration(int $generation): VersionGroup
    {
        $mapping = [
            '1' => 'red-blue',
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

    private function formatLevel(int $level)
    {
        if ($level === 1) {
            return 'Départ';
        }

        return $level;
    }
}