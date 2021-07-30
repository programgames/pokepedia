<?php

namespace App\Api\Pokepedia;

use App\Api\Pokepedia\Client\PokepediaBaseInformationClient;
use App\Satanizer\PokepediaBaseInformationSatanizer;
use Doctrine\DBAL\Driver\Connection;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Contracts\Cache\ItemInterface;

//extract and transform pokemon base informations into entities from pokepedia
class PokepediaBasePokemonInformationApi
{
    private AbstractAdapter $cache;
    private PokepediaBaseInformationSatanizer $satanizer;
    private PokepediaBaseInformationClient $client;

    public function __construct(PokepediaBaseInformationSatanizer $satanizer, PokepediaBaseInformationClient $client, Connection $connection)
    {
        $this->satanizer = $satanizer;
        $this->client = $client;

        $this->cache = new PdoAdapter($connection);

    }

    public function getPokepediaTypeOneName(string $name): string
    {
        $infos = $this->cache->get(
            sprintf('pokepedia.wikitext.%s,%s', str_replace(':','',$name), 'family'),
            function (ItemInterface $item) use ($name) {
                return $this->client->getBasePokemonInformations(
                    $name,
                );
            }
        );

        return $this->satanizer->extractType1($infos);
    }
}
