<?php


namespace App\Api\Bulbapedia;


use App\Api\Bulbapedia\Client\BulbapediaMoveClient;
use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;
use App\Satanizer\BulbapediaMoveSatanizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class BulbapediaMovesAPI
{
    private EntityManagerInterface $entityManager;
    private FilesystemAdapter $cache;
    private BulbapediaMoveSatanizer $moveSatanizer;
    private BulbapediaMoveClient $moveClient;

    public function __construct(EntityManagerInterface $entityManager, BulbapediaMoveSatanizer $moveSatanizer, BulbapediaMoveClient $moveClient)
    {
        $this->entityManager = $entityManager;
        $this->moveSatanizer = $moveSatanizer;
        $this->moveClient = $moveClient;

        $this->cache = new FilesystemAdapter();
    }

    public function getTutorMoves(Pokemon $pokemon, string $generation,bool $lgpe = false)
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::BULBAPEDIA_TUTOR_WIKI_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation,$lgpe) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::BULBAPEDIA_TUTORING_TYPE_LABEL,
                    $lgpe
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation,MoveSetHelper::BULBAPEDIA_TUTOR_WIKI_TYPE);
    }

    public function getLevelMoves(Pokemon $pokemon, int $generation,bool $lgpe = false)
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation,$lgpe) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::BULBAPEDIA_LEVELING_UP_TYPE_LABEL,
                    $lgpe
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation,MoveSetHelper::BULBAPEDIA_LEVEL_WIKI_TYPE);
    }

    public function getMachineMoves(Pokemon $pokemon, int $generation,bool $lgpe = false)
    {
        $moves = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::MACHINE_TYPE),
            function (ItemInterface $item) use ($pokemon, $generation,$lgpe) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $pokemon,
                    $generation,
                    MoveSetHelper::getBulbapediaMachineLabelByGeneration($generation),
                    $lgpe
                );
            }
        );

        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation,MoveSetHelper::BULBAPEDIA_TM_WIKI_TYPE);
    }
}
