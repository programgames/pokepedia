<?php

namespace App\Api\Bulbapedia;

use App\Api\Bulbapedia\Client\BulbapediaMachineClient;
use App\Satanizer\BulbapediaMachineSatanizer;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

// Api class to manipulate machine information easily from bulbapedia
class BulbapediaMachineAPI
{
    private FilesystemAdapter $cache;
    private BulbapediaMachineClient $machineClient;
    private BulbapediaMachineSatanizer $bulbapediaMachineSatanizer;

    public function __construct(BulbapediaMachineClient $machineClient, BulbapediaMachineSatanizer $bulbapediaMachineSatanizer)
    {
        $this->cache = new FilesystemAdapter();

        $this->machineClient = $machineClient;
        $this->bulbapediaMachineSatanizer = $bulbapediaMachineSatanizer;
    }

    /**
     * @param string $itemName
     * @param $generation
     * @return string
     * @throws InvalidArgumentException
     */
    public function getMoveNameByItemAndGeneration(string $itemName, $generation): string
    {
        $machineInfos = $this->cache->get(
            sprintf('bulbapedia.wikitext.machine.%s', $itemName),
            function () use ($itemName) {
                return $this->machineClient->getMachineInformation($itemName);
            }
        );

        return $this->bulbapediaMachineSatanizer->getMoveNameByItem($machineInfos, $generation);
    }
}
