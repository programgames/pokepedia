<?php


namespace App\Api\Pokepedia;


use App\Api\Pokepedia\Client\PokepediaMoveApiClient;
use App\Helper\MoveSetHelper;
use App\Satanizer\PokepediaLevelMoveSatanizer;
use Doctrine\DBAL\Connection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Contracts\Cache\ItemInterface;

//extract and transform pokemon move informations into entities from pokepedia
class PokepediaMoveApi
{
    private AbstractAdapter $cache;
    private PokepediaLevelMoveSatanizer $moveSatanizer;
    private PokepediaMoveApiClient $moveClient;

    public function __construct(PokepediaLevelMoveSatanizer $moveSatanizer, PokepediaMoveApiClient $moveClient, Connection $connection)
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

    private function getLevelMovesFromCache(string $name, int $generation)
    {
        return $this->cache->get(
            sprintf('pokepedia.wikitext.%s,%s.%s', $name, $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function (ItemInterface $item) use ($name, $generation) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $name,
                    $generation,
                    MoveSetHelper::POKEPEDIA_LEVELING_UP_TYPE_LABEL
                );
            }
        );
    }

    public function getLevelMoves(string $name, int $generation): array
    {
        $moves = $this->getLevelMovesFromCache($name,$generation);

        return $this->moveSatanizer->checkAndSanitizeMoves($moves);
    }

    public function getRawWikitext(string $name, int $generation)
    {
        $formmatted = $this->getLevelMovesFromCache($name,$generation);
        array_shift($formmatted);
        return implode(PHP_EOL,$formmatted);
    }
}
