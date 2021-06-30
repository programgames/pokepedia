<?php


namespace App\Api\Client;


use App\Entity\Pokemon;
use App\Entity\PokemonName;
use App\Formatter\StringHelper;
use App\Generation\GenerationHelper;
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

    public function getMovesByPokemonGenerationAndType(Pokemon $pokemon, int $generation, string $moveType): array
    {
        $pokemonName = $this->entityManager->getRepository(PokemonName::class)
            ->findOneBy(
                [
                    'pokemon' => $pokemon,
                    'languageId' => 9
                ]
            );

        $sections = $this->getMoveSections($pokemon, $generation);

        $url = strtr(
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%pokemon%' => str_replace('’','%27',$pokemonName->getName()),
                '%generation%' => GenerationHelper::genNumberToLitteral($generation),
                '%section%' => $sections[$moveType]
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
        $pokemonName = $this->entityManager->getRepository(PokemonName::class)
            ->findOneBy(
                [
                    'pokemon' => $pokemon,
                    'languageId' => 9
                ]
            );

        $formattedSections = [];
        $sectionsUrl = strtr(
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=sections&errorformat=wikitext&disabletoc=1',
            [
                '%pokemon%' => str_replace('’','%27',$pokemonName->getName()),
                '%generation%' => GenerationHelper::genNumberToLitteral($generation),
            ]
        );

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