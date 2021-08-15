<?php

namespace App\Api\Bulbapedia\Client;

use App\Api\Wikimedia\Wikimedia\Client;
use App\Entity\Pokemon;
use App\Entity\SpecyName;
use App\Helper\GenerationHelper;
use App\Helper\StringHelper;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;

// Get wiki text from pokemon by move learn method and generation https://bulbapedia.bulbagarden.net/wiki/Mewtwo_(Pok%C3%A9mon)#By_leveling_up
class BulbapediaMoveClient
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMovesByPokemonGenerationAndType(Pokemon $pokemon, int $generation, string $moveType, bool $lgpe = false): array
    {
        if ($lgpe && ($pokemon->getName() === 'meltan' || $pokemon->getName() === 'melmetal')) {
            $lgpe = false;
        }
        if ($lgpe && $generation !== 7) {
            throw new RuntimeException('Using lgpe flag is not possible without using gen 7');
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
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)%generation%&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%pokemon%' => str_replace('’', '%27', $pokemonName),
                '%generation%' => $generation === 8 ? '' : '/Generation_' . GenerationHelper::convertGenerationToBulbapediaRomanNotation($generation) . '_learnset',
                '%section%' => $sections[$lgpe ? $moveType . '-2' : $moveType]
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

    private function getMoveSections(Pokemon $pokemon, int $generation): array
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
            'https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)%generation%&prop=sections&errorformat=wikitext&disabletoc=1',
            [
                '%pokemon%' => str_replace('’', '%27', $pokemonName),
                '%generation%' => $generation === 8 ? '' : '/Generation_' . GenerationHelper::convertGenerationToBulbapediaRomanNotation($generation) . '_learnset',
            ]
        );

        $content = Client::parse($sectionsUrl);

        foreach ($content['parse']['sections'] as $section) {
            if (array_key_exists($section['line'], $formattedSections)) {
                $formattedSections[$section['line'] . '-2'] = $section['index'];
            } else {
                $formattedSections[$section['line']] = $section['index'];
            }
        }
        return $formattedSections;
    }
}
