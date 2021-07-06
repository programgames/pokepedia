<?php


namespace App\Api\Pokepedia;


use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class PokepediaMoveApi
{
    private EntityManagerInterface $entityManager;
    private FilesystemAdapter $cache;
//    private MoveSatanizer $moveSatanizer;
    private PokepediaMoveApiClient $moveClient;

    /**
     * PokepediaMoveApi constructor.
     * @param EntityManagerInterface $entityManager
     * @param PokepediaMoveApiClient $moveClient
     */
    public function __construct(EntityManagerInterface $entityManager, PokepediaMoveApiClient $moveClient)
    {
        $this->entityManager = $entityManager;
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
            sprintf('pokepedia.wikitext.%s,%s.%s',$name, $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function (ItemInterface $item) use ($name, $generation) {
                return $this->moveClient->getMovesByPokemonGenerationAndType(
                    $name,
                    $generation,
                    MoveSetHelper::POKEPEDIA_LEVELING_UP_TYPE_LABEL
                );
            }
        );

//        return $this->moveSatanizer->checkAndSanitizeMoves($moves, $generation, MoveSetHelper::BULBAPEDIA_LEVEL_WIKI_TYPE);
    }
}