<?php

namespace App\Api\Bulbapedia\Client;

use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class BulbapediaAvailabilityClient
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAvailabilitiesByGeneration(string $generation): array
    {
        $sections = $this->getSections();

        $url = strtr(
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=List_of_Pok%C3%A9mon_by_availability&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%section%' => $sections[$generation],
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
                return StringHelper::clearBracesAndBrs($value);
            },
            $wikitext
        );
    }

    private function getSections()
    {

        $formattedSections = [];
        $sectionsUrl =
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=List_of_Pok%C3%A9mon_by_availability&prop=sections&errorformat=wikitext&disabletoc=1';


        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $sectionsUrl);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);

        foreach ($json['parse']['sections'] as $section) {
                $formattedSections[$section['line']] = $section['index'];
        }
        return $formattedSections;
    }
}
