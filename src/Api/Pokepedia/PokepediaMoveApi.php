<?php


namespace App\Api\Pokepedia;


use App\Helper\MoveSetHelper;
use App\Satanizer\LevelMoveSatanizer;
use Doctrine\DBAL\Connection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class PokepediaMoveApi
{
    private AbstractAdapter $cache;
    private LevelMoveSatanizer $moveSatanizer;
    private PokepediaMoveApiClient $moveClient;

    public function __construct(LevelMoveSatanizer $moveSatanizer, PokepediaMoveApiClient $moveClient, Connection $connection)
    {
        $this->moveSatanizer = $moveSatanizer;
        $this->moveClient = $moveClient;

        $this->cache = new PdoAdapter($connection);

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

    public function getLevelMoves(string $name, int $generation): array
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