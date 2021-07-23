<?php


namespace App\Api\Bulbapedia;


use App\Api\Bulbapedia\Client\BulbapediaMoveClient;
use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;
use App\Satanizer\BulbapediaMoveSatanizer;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class BulbapediaMovesAPI
{
    private AbstractAdapter $cache;
    private BulbapediaMoveSatanizer $moveSatanizer;
    private BulbapediaMoveClient $moveClient;

    /**
     * BulbapediaMovesAPI constructor.
     * @param BulbapediaMoveSatanizer $moveSatanizer
     * @param BulbapediaMoveClient $moveClient
     * @param Connection $connection
     */
    public function __construct(BulbapediaMoveSatanizer $moveSatanizer, BulbapediaMoveClient $moveClient, Connection $connection)
    {
        $this->moveSatanizer = $moveSatanizer;
        $this->moveClient = $moveClient;

        $this->cache = new PdoAdapter(
            $connection
        );
    }

    public function getTutorMoves(Pokemon $pokemon, string $generation, bool $lgpe = false)
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::BULBAPEDIA_TUTOR_WIKI_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation, $lgpe) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::BULBAPEDIA_TUTORING_TYPE_LABEL,
                    $lgpe
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation, MoveSetHelper::BULBAPEDIA_TUTOR_WIKI_TYPE);
    }

    public function getLevelMoves(Pokemon $pokemon, int $generation, bool $lgpe = false)
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation, $lgpe) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::BULBAPEDIA_LEVELING_UP_TYPE_LABEL,
                    $lgpe
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation, MoveSetHelper::BULBAPEDIA_LEVEL_WIKI_TYPE);
    }

    public function getEggMoves(Pokemon $pokemon, int $generation, bool $lgpe = false)
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::BULBAPEDIA_BREEDING_WIKI_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation, $lgpe) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::BULBAPEDIA_BREEDING_TYPE_LABEL,
                    $lgpe
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation, MoveSetHelper::BULBAPEDIA_BREEDING_WIKI_TYPE);
    }

    public function getMachineMoves(Pokemon $pokemon, int $generation, bool $lgpe = false)
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::MACHINE_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation, $lgpe) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::getBulbapediaMachineLabelByGeneration($generation),
                    $lgpe
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation, MoveSetHelper::BULBAPEDIA_TM_WIKI_TYPE);
    }
}
