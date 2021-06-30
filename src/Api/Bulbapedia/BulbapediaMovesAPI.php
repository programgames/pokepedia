<?php


namespace App\Api\Bulbapedia;


use App\Api\Client\BulbapediaMoveClient;
use App\Entity\Pokemon;
use App\MoveSet\MoveSetHelper;
use App\Sanitize\MoveSatanizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class BulbapediaMovesAPI
{
    private EntityManagerInterface $entityManager;
    private FilesystemAdapter $cache;
    private MoveSatanizer $moveSatanizer;
    private BulbapediaMoveClient $moveClient;

    public function __construct(EntityManagerInterface $entityManager, MoveSatanizer $moveSatanizer, BulbapediaMoveClient $moveClient)
    {
        $this->entityManager = $entityManager;
        $this->moveSatanizer = $moveSatanizer;
        $this->moveClient = $moveClient;

        $this->cache = new FilesystemAdapter();
    }

    public function getTutorMoves(Pokemon $pokemon, string $generation)
    {
        $moves = $this->cache->get(
            sprintf('wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::TUTORING_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::BULBAPEDIA_LEVELING_UP_TYPE_LABEL
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation,MoveSetHelper::BULBAPEDIA_TUTOR_WIKI_TYPE);
    }

    public function getLevelMoves(Pokemon $pokemon, int $generation)
    {
        $moves = $this->cache->get(
            sprintf('wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::BULBAPEDIA_LEVELING_UP_TYPE_LABEL
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation,MoveSetHelper::BULBAPEDIA_LEVEL_WIKI_TYPE);
    }
}
