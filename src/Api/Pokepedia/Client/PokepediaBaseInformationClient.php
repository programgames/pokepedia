<?php


namespace App\Api\Pokepedia\Client;


use App\Exception\InvalidResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class PokepediaBaseInformationClient
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getBasePokemonInformations(string $name): array
    {

        $url = strtr(
            'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%pokemon%&prop=wikitext&errorformat=wikitext&section=0&disabletoc=1',
            [
                '%pokemon%' => str_replace('’', '%27', $name),
            ]
        );

        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $url);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);
        if (!array_key_exists('parse', $json)) {
            throw new InvalidResponse(sprintf('Invalid response from pokepedia for pokemon %s', $name));
        }
        $wikitext = reset($json['parse']['wikitext']);
        $wikitext = preg_split('/$\R?^/m', $wikitext);
        return $wikitext;
    }

    private function getMoveSections(string $name, int $generation): array
    {
        $formattedSections = [];

        if ($generation < 7) {
            $sectionsUrl = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%pokemon%/G%C3%A9n%C3%A9ration_%generation%&prop=sections&errorformat=wikitext&disabletoc=1',
                [
                    '%pokemon%' => str_replace('’', '%27', $name),
                    '%generation%' => $generation,
                ]
            );
        } else {
            $sectionsUrl = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%pokemon%&prop=sections&errorformat=wikitext',
                [
                    '%pokemon%' => str_replace('’', '%27', $name),
                    '%generation%' => $generation,
                ]
            );
        }

        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $sectionsUrl);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);

        if (!array_key_exists('parse', $json)) {
            throw new InvalidResponse(sprintf('Invalid response from pokepedia for pokemon %s generation %s', $name, $generation));
        }
        foreach ($json['parse']['sections'] as $section) {
            $formattedSections[$section['line']] = $section['index'];
        }

        return $formattedSections;
    }
}