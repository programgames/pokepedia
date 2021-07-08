<?php


namespace App\Api\Pokepedia;


use App\Formatter\Pokepedia\PokepediaTutorMoveFormatter;
use App\Helper\MoveSetHelper;
use App\Satanizer\TutorMoveSatanizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class PokepediaMoveApi
{
    private FilesystemAdapter $cache;
    private TutorMoveSatanizer $moveSatanizer;
    private PokepediaMoveApiClient $moveClient;

    public function __construct(TutorMoveSatanizer $moveSatanizer, PokepediaMoveApiClient $moveClient)
    {
        $this->moveSatanizer = $moveSatanizer;
        $this->moveClient = $moveClient;

        $this->cache = new FilesystemAdapter();

    }

//    public function getTutorMoves(Pokemon $pokemon, string $generation)
//    {
//        $moves = $this->cache->get(
//            sprintf('wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::TUTORING_TYPE),
//            function (ItemInterface $item) use ($pokemon, $generation) {
//                return $this->moveClient->getMovesByPokemonGenerationAndType(
//                    $pokemon,
//                    $generation,
//                    MoveSetHelper::POKEPEDIA_LEVELING_UP_TYPE_LABEL
//                );
//            }
//        );
//
//        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation, MoveSetHelper::BULBAPEDIA_TUTOR_WIKI_TYPE);
//    }

    public function getLevelMoves(string $name, int $generation)
    {
        $moves = $this->cache->get(
            sprintf('pokepedia.wikitext.%s,%s.%s', $name, $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function (ItemInterface $item) use ($name, $generation) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $name,
                    $generation,
                    MoveSetHelper::POKEPEDIA_LEVELING_UP_TYPE_LABEL
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves);
    }
}