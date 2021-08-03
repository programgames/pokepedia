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
    private PokepediaBaseInformationSatanizer $satanizer;
    private PokepediaBaseInformationClient $client;

    public function __construct(PokepediaBaseInformationSatanizer $satanizer, PokepediaBaseInformationClient $client)
    {
        $this->satanizer = $satanizer;
        $this->client = $client;
    }

    public function getPokepediaTypeOneName(string $name): string
    {
        $infos = $this->client->getBasePokemonInformations($name);

        return $this->satanizer->extractType1($infos);
    }
}
