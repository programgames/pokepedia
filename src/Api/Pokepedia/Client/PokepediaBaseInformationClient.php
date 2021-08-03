<?php

namespace App\Api\Pokepedia\Client;

use App\Api\Http\Wikimedia\Client;
use App\Exception\InvalidResponse;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

// Get wiki text from pokemon https://www.pokepedia.fr/index.php?title=Mewtwo&action=edit&section=0
class PokepediaBaseInformationClient
{
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
}
