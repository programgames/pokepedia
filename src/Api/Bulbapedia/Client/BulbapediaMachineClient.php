<?php

namespace App\Api\Bulbapedia\Client;

use App\Api\Wikimedia\Wikimedia\Client;
use App\Helper\StringHelper;

// Get wiki text from item https://bulbapedia.bulbagarden.net/wiki/TM01
class BulbapediaMachineClient
{
    public function getMachineInformation(string $itemName): array
    {
        $url = strtr(
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%itemName%&prop=wikitext&errorformat=wikitext&section=0&disabletoc=1',
            [
                '%itemName%' => $itemName,
            ]
        );

        $content = Client::parse($url);
        $wikitext = reset($content['parse']['wikitext']);
        $wikitext = preg_split('/$\R?^/m', $wikitext);
        return array_map(
            static function ($value) {
                return StringHelper::clearBracesAndBrs($value);
            },
            $wikitext
        );
    }
}
