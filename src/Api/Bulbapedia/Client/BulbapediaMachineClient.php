<?php

namespace App\Api\Bulbapedia\Client;

use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class BulbapediaMachineClient
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMachineInformation(string $itemName): array
    {
        $url = strtr(
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%itemName%&prop=wikitext&errorformat=wikitext&section=0&disabletoc=1',
            [
                '%itemName%' => $itemName,
            ]
        );


        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $url);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);
        $wikitext = reset($json['parse']['wikitext']);
        $wikitext = preg_split('/$\R?^/m', $wikitext);
        return array_map(
            function ($value) {
                return StringHelper::clearBraces($value);
            },
            $wikitext
        );
    }
}