<?php

namespace App\Api\Bulbapedia;

use App\Api\Bulbapedia\Client\BulbapediaMoveClient;
use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;
use App\Satanizer\BulbapediaMoveSatanizer;
use Doctrine\DBAL\Connection;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;

// Api class to manipulate pokemon moves easily from bulbapedia
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

    /**
     * @param Pokemon $pokemon
     * @param string $generation
     * @param bool $lgpe
     * @return array
     * @throws InvalidArgumentException
     */
    public function getTutorMoves(Pokemon $pokemon, string $generation, bool $lgpe = false): array
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.move.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::BULBAPEDIA_TUTOR_WIKI_TYPE),
            function () use ($pokemon, $generation, $lgpe) {
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

    /**
     * @param Pokemon $pokemon
     * @param int $generation
     * @param bool $lgpe
     * @return array
     * @throws InvalidArgumentException
     */
    public function getLevelMoves(Pokemon $pokemon, int $generation, bool $lgpe = false): array
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.move.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function () use ($pokemon, $generation, $lgpe) {
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

    /**
     * @param Pokemon $pokemon
     * @param int $generation
     * @param bool $lgpe
     * @return array
     * @throws InvalidArgumentException
     */
    public function getEggMoves(Pokemon $pokemon, int $generation, bool $lgpe = false): array
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.move.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::BULBAPEDIA_BREEDING_WIKI_TYPE),
            function () use ($pokemon, $generation, $lgpe) {
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

    /**
     * @param Pokemon $pokemon
     * @param int $generation
     * @param bool $lgpe
     * @return array
     * @throws InvalidArgumentException
     */
    public function getMachineMoves(Pokemon $pokemon, int $generation, bool $lgpe = false): array
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.move.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::MACHINE_TYPE),
            function () use ($pokemon, $generation, $lgpe) {
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
