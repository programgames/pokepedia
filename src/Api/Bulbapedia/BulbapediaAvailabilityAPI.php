<?php


namespace App\Api\Bulbapedia;


use App\Api\Bulbapedia\Client\BulbapediaAvailabilityClient;
use App\Satanizer\BulbapediaAvailabilitySatanizer;
use App\Satanizer\BulbapediaMachineSatanizer;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class BulbapediaAvailabilityAPI
{
    private FilesystemAdapter $cache;
    private BulbapediaAvailabilityClient $availabilityClient;
    private BulbapediaAvailabilitySatanizer $availabilitySatanizer;

    public function __construct(BulbapediaAvailabilityClient $machineClient, BulbapediaAvailabilitySatanizer $availabilitySatanizer)
    {
        $this->cache = new FilesystemAdapter();

        $this->availabilityClient = $machineClient;
        $this->availabilitySatanizer = $availabilitySatanizer;
    }

    public function getAvailabilitiesByGeneration(string $generation)
    {
        $machineInfos = $this->cache->get(
            sprintf('bulbapedia.wikitext.%s',$generation),
            function (ItemInterface $item) use ($generation) {
                return $this->availabilityClient->getAvailabilitiesByGeneration($generation);
            }
        );

        return $this->availabilitySatanizer->sanitizeAvailabilities($machineInfos);
    }
}
