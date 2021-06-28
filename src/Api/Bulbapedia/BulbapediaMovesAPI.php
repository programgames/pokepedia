<?php


namespace App\Api\Bulbapedia;


use App\Entity\Pokemon;
use App\Exception\EmptyMoveSetException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class BulbapediaMovesAPI
{
    public function getTutorMoves(Pokemon $pokemon, string $generation)
    {
        $sections = $this->getSections($pokemon, $generation);

        $url = strtr('https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%pokemon%' => $pokemon->getEnglishName(),
                '%generation%' => $generation,
                '%section%' => $sections['By tutoring']
            ]);


        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $url);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);
        $wikitext = reset($json['parse']['wikitext']);
        $moves = preg_split('/$\R?^/m', $wikitext);

        $moveNames = [];

        foreach ($moves as $move) {
            $move = str_replace(array('{', '}'), '', $move);
            if(!preg_match('/tutor\d/',$move, $matches)) {
                continue;
            }

            $moveNames[] = explode('|', $move);
        }
        if(empty($moveNames)) {
            throw new EmptyMoveSetException(sprintf('Empty moveset for pokemon %s in gen %s',$pokemon->getEnglishName(),$generation));
        }

        return $moveNames;
    }

    public function getLevelMoves(Pokemon $pokemon, string $generation)
    {
        $sections = $this->getSections($pokemon, $generation);

        $url = strtr('https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%pokemon%' => $pokemon->getEnglishName(),
                '%generation%' => $generation,
                '%section%' => $sections['By tutoring']
            ]);


        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $url);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);
        $wikitext = reset($json['parse']['wikitext']);
        $moves = preg_split('/$\R?^/m', $wikitext);

        $moveNames = [];

        foreach ($moves as $move) {
            $move = str_replace(array('{', '}'), '', $move);
            if(!preg_match('/tutor\d/',$move, $matches)) {
                continue;
            }

            $moveNames[] = explode('|', $move);
        }
        if(empty($moveNames)) {
            throw new EmptyMoveSetException(sprintf('Empty moveset for pokemon %s in gen %s',$pokemon->getEnglishName(),$generation));
        }

        return $moveNames;
    }

    private function getSections(Pokemon $pokemon, string $generation)
    {
        $formattedSections = [];
        $sectionsUrl = strtr('https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=sections&errorformat=wikitext&disabletoc=1',
            [
                '%pokemon%' => $pokemon->getEnglishName(),
                '%generation%' => $generation,
            ]);

        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $sectionsUrl);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);

        foreach ($json['parse']['sections'] as $section){
            $formattedSections[$section['line']] = $section['index'];
        }

        return $formattedSections;
    }
}
