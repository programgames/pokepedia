<?php

namespace App\Api\Bulbapedia\Client;

use App\Entity\Pokemon;
use App\Entity\SpecyName;
use App\Helper\GenerationHelper;
use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class BulbapediaMoveClient
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMovesByPokemonGenerationAndType(Pokemon $pokemon, int $generation, string $moveType,bool $lgpe = false): array
    {
        if($lgpe && $generation != 7 ) {
            throw new \RuntimeException('Using lgpe flag is not possible without using gen 7');
        }
        $pokemonName = ($this->entityManager->getRepository(SpecyName::class)
            ->findOneBy(
                [
                    'pokemonSpecy' => $pokemon->getPokemonSpecy(),
                    'language' => 9
                ]
            ))->getName();

        $sections = $this->getMoveSections($pokemon, $generation);

        $url = strtr(
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%pokemon%' => str_replace('’','%27',$pokemonName),
                '%generation%' => GenerationHelper::convertGenerationToBulbapediaRomanNotation($generation),
                '%section%' => $sections[$lgpe ? $moveType .'-2' : $moveType ]
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

    private function getMoveSections(Pokemon $pokemon, int $generation)
    {
        $pokemonName = ($this->entityManager->getRepository(SpecyName::class)
            ->findOneBy(
                [
                    'pokemonSpecy' => $pokemon->getPokemonSpecy(),
                    'language' => 9
                ]
            ))->getName();

        $formattedSections = [];
        $sectionsUrl = strtr(
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=sections&errorformat=wikitext&disabletoc=1',
            [
                '%pokemon%' => str_replace('’','%27',$pokemonName),
                '%generation%' => GenerationHelper::convertGenerationToBulbapediaRomanNotation($generation),
            ]
        );

        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $sectionsUrl);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);

        foreach ($json['parse']['sections'] as $section) {
            if(array_key_exists($section['line'],$formattedSections)) {
                $formattedSections[$section['line'].'-2'] = $section['index'];

            } else {
                $formattedSections[$section['line']] = $section['index'];
            }
        }
        return $formattedSections;
    }
}