<?php

namespace App\Api\Pokepedia\Client;

use App\Api\Http\Wikimedia\Client;
use App\Exception\InvalidResponse;
use App\Exception\SectionNotFoundException;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

// Get wiki text from pokemon by move learn method and generation https://www.pokepedia.fr/Mewtwo#Par_mont.C3.A9e_en_niveau
class PokepediaMoveApiClient
{
    public function getMovesByPokemonGenerationAndType(string $name, int $generation, string $moveType): array
    {
        $sections = $this->getMoveSections($name, $generation);

        if (!array_key_exists($moveType, $sections)) {
            throw  new SectionNotFoundException(
                sprintf(
                    "Section %s not found for pokemon %s , generation %s",
                    $moveType,
                    $name,
                    $generation
                )
            );
        }
        if ($generation < 7) {
            $section = $sections[$moveType];
            $page = strtr(
                '%pokemon%/Génération_%generation%',
                [
                    '%generation%' => $generation,
                    '%pokemon%' => str_replace(['’', '\'',' '], ['%27','%27','_'], $name),
                ]
            );
            $url = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%page%&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
                [
                    '%page%' => $page,
                    '%section%' => $section,
                ]
            );
        } else {
            $section = $sections[$moveType] + ($generation === 7 ? 1 : 2);
            $page = str_replace(['’', '\''], '%27', $name);

            $url = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%page%&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
                [
                    '%page%' => $page,
                    '%section%' => $section
                ]
            );
        }

        $content = Client::parse($url);
        $wikitext = reset($content['parse']['wikitext']);
        $wikitext = preg_split('/$\R?^/m', $wikitext);

        return [
            'wikitext' => $wikitext,
            'section' => $section,
            'page' => $page
        ];
    }

    private function getMoveSections(string $name, int $generation): array
    {
        $formattedSections = [];

        if ($generation < 7) {
            $sectionsUrl = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%pokemon%/G%C3%A9n%C3%A9ration_%generation%&prop=sections&errorformat=wikitext&disabletoc=1',
                [
                    '%pokemon%' => str_replace(['’', '\'',' '], ['%27','%27','_'], $name),
                    '%generation%' => $generation,
                ]
            );
        } else {
            $sectionsUrl = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%pokemon%&prop=sections&errorformat=wikitext',
                [
                    '%pokemon%' => str_replace(['’', '\'',' '], ['%27','%27','_'], $name),
                    '%generation%' => $generation,
                ]
            );
        }

        $content = Client::parse($sectionsUrl);
        foreach ($content['parse']['sections'] as $section) {
            $formattedSections[$section['line']] = $section['index'];
        }

        return $formattedSections;
    }
}
