<?php


namespace App\Api\Bulbapedia;


use App\Entity\Pokemon;
use App\Exception\EmptyMoveSetException;
use App\MoveSet\MoveSetHelper;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class BulbapediaMovesAPI
{
    public function getTutorMoves(Pokemon $pokemon, string $generation)
    {
        $moves = $this->getMovesByPokemonGenerationAndType($pokemon,$generation,MoveSetHelper::TUTORING_TYPE);

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
        $moves = $this->getMovesByPokemonGenerationAndType($pokemon,$generation,MoveSetHelper::BULBAPEDIA_LEVELING_UP_TYPE_LABEL);

        $moveNames = [];

        foreach ($moves as $move) {
            $move = str_replace(array('{', '}'), '', $move);
            if(!preg_match('/level\d/',$move, $matches)) {
                continue;
            }

            $moveNames[] = explode('|', $move);
        }
        if(empty($moveNames)) {
            throw new EmptyMoveSetException(sprintf('Empty moveset for pokemon %s in gen %s',$pokemon->getEnglishName(),$generation));
        }

        return $moveNames;
    }

    private function getMoveSections(Pokemon $pokemon, string $generation)
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

    private function getMovesByPokemonGenerationAndType(Pokemon $pokemon, string $generation, string $moveType)
    {
        $sections = $this->getMoveSections($pokemon, $generation);

        $url = strtr('https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%pokemon%' => $pokemon->getEnglishName(),
                '%generation%' => $generation,
                '%section%' => $sections[$moveType]
            ]);


        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $url);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);
        $wikitext = reset($json['parse']['wikitext']);
        return preg_split('/$\R?^/m', $wikitext);
    }
}
