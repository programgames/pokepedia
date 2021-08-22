<?php

namespace App\Api\Pokepedia;

use App\Api\Pokepedia\Client\PokepediaMoveApiClient;
use App\Helper\MoveSetHelper;
use App\Satanizer\PokepediaLevelMoveSatanizer;
use Doctrine\DBAL\Connection;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;

//extract and transform pokemon move informations into entities from pokepedia


    /**
     * @param string $name
     * @param int $generation
     * @return mixed
     * @throws InvalidArgumentException
     */
    private function getLevelMovesFromCache(string $name, int $generation)
    {
        return $this->cache->get(
            sprintf('pokepedia.wikitext.pokemonmove.%s,%s.%s', $name, $generation, MoveSetHelper::LEVELING_UP_TYPE),
            function () use ($name, $generation) {
                return $this->moveClient->getPokemonMoves(
                    $name,
                    $generation,
                    MoveSetHelper::LEVELING_UP_TYPE
                );
            }
        );
    }

    /**
     * @param string $name
     * @param int $generation
     * @return array
     * @throws InvalidArgumentException
     */
    public function getLevelMoves(string $name, int $generation): array
    {
        $movesData = $this->getLevelMovesFromCache($name, $generation);

        $movesData['satanized'] = $this->moveSatanizer->checkAndSanitizeMoves($movesData['wikitext']);

        return $movesData;
    }

    /**
     * @param string $name
     * @param int $generation
     * @return string
     * @throws InvalidArgumentException
     */
    public function getRawWikitext(string $name, int $generation): string
    {
        $formated = $this->getLevelMovesFromCache($name, $generation);
        return implode(PHP_EOL, $formated['wikitext']);
    }
}
