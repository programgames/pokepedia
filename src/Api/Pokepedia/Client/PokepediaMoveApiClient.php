<?php

namespace App\Api\Pokepedia\Client;

use App\Exception\NotAvailableException;
use App\Exception\NotImplementedException;
use App\Helper\MoveSetHelper;


// Get wiki text from pokemon by move learn method and generation https://www.pokepedia.fr/Mewtwo#Par_mont.C3.A9e_en_niveau
class PokepediaMoveApiClient
{
    private PokepediaClient $client;

    /**
     * PokepediaMoveApiClient constructor.
     * @param PokepediaClient $client
     */
    public function __construct(PokepediaClient $client)
    {
        $this->client = $client;
    }

    public function getPokemonMoves(string $name, int $generation, string $moveType, string $versionGroupName = null, bool $DT = false): array
    {
        if (($moveType === MoveSetHelper::TUTOR_TYPE || $moveType === MoveSetHelper::MACHINE_TYPE) && !$versionGroupName) {
            throw new \RuntimeException(sprintf('argument $versionGroupName is required for %s type', $moveType));
        }
        $sections = $this->getMoveSections($name, $generation);

        $section = $this->getSectionIndexByPokepediaMoveTypeAndGeneration($moveType, $sections, $generation, $versionGroupName, $DT);
        if ($generation < 7) {
            $page = strtr(
                '%pokemon%/Génération_%generation%',
                [
                    '%generation%' => $generation,
                    '%pokemon%' => str_replace(['’', '\'', ' '], ['%27', '%27', '_'], $name),
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
            $page = str_replace(['’', '\''], '%27', $name);

            $url = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%page%&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
                [
                    '%page%' => $page,
                    '%section%' => $section
                ]
            );
        }

        $content = $this->client->parse($url);
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
        if ($generation < 7) {
            $sectionsUrl = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%pokemon%/G%C3%A9n%C3%A9ration_%generation%&prop=sections&errorformat=wikitext&disabletoc=1',
                [
                    '%pokemon%' => str_replace(['’', '\'', ' '], ['%27', '%27', '_'], $name),
                    '%generation%' => $generation,
                ]
            );
        } else {
            $sectionsUrl = strtr(
                'https://www.pokepedia.fr/api.php?action=parse&format=json&page=%pokemon%&prop=sections&errorformat=wikitext',
                [
                    '%pokemon%' => str_replace(['’', '\'', ' '], ['%27', '%27', '_'], $name),
                    '%generation%' => $generation,
                ]
            );
        }

        return $this->client->formatSectionsByUrl($sectionsUrl);
    }

    private function getSectionIndexByPokepediaMoveTypeAndGeneration(string $moveType, array $sections, int $generation, string $versionGroupName = null, bool $DT = false): int
    {
        if ($moveType === MoveSetHelper::LEVELING_UP_TYPE && $generation <= 6) {
            return $sections['Capacités apprises//Par montée en niveau'];
        } elseif ($moveType === MoveSetHelper::LEVELING_UP_TYPE && $generation === 7) {
            return $sections['Capacités apprises//Par montée en niveau//Septième génération'];
        } elseif ($moveType === MoveSetHelper::LEVELING_UP_TYPE && $generation === 8) {
            return $sections['Capacités apprises//Par montée en niveau//Huitième génération'];
        } elseif ($moveType === MoveSetHelper::MACHINE_TYPE && $generation <= 6) {
            return $sections['Capacités apprises//Par CT/CS'];
        } elseif ($moveType === MoveSetHelper::MACHINE_TYPE && $generation === 7 && $versionGroupName !== "Pokémon : Let's Go, Pikachu et Let's Go, Évoli") {
            return $sections['Capacités apprises//Par CT/CS//Septième génération//Pokémon Soleil et Lune et Pokémon Ultra-Soleil et Ultra-Lune'];
        } elseif ($moveType === MoveSetHelper::MACHINE_TYPE && $generation === 7 && $versionGroupName === "Pokémon : Let's Go, Pikachu et Let's Go, Évoli") {
            return $sections["Capacités apprises//Par CT/CS//Septième génération//Pokémon : Let's Go, Pikachu et Let's Go, Évoli"];
        } elseif ($moveType === MoveSetHelper::MACHINE_TYPE && $generation === 8 && !$DT) {
            return $sections["Capacités apprises//Par CT/CS//Huitième génération"];
        } elseif ($moveType === MoveSetHelper::MACHINE_TYPE && $generation === 8 && $DT) {
            return $sections["Capacités apprises//Par DT//Huitième génération"];
        } elseif ($moveType === MoveSetHelper::EGG_TYPE && $generation === 1) {
            throw new NotAvailableException("egg mooves are not available in gen 1");
        } elseif ($moveType === MoveSetHelper::EGG_TYPE && $generation >= 2 && $generation <= 6) {
            return $sections["Capacités apprises//Par reproduction"];
        } elseif ($moveType === MoveSetHelper::EGG_TYPE && $generation === 7) {
            return $sections["Capacités apprises//Par reproduction//Septième génération"];
        } elseif ($moveType === MoveSetHelper::EGG_TYPE && $generation === 8) {
            return $sections["Capacités apprises//Par reproduction//Huitième génération"];
        } elseif ($moveType === MoveSetHelper::TUTOR_TYPE && $generation === 1) {
            throw new NotAvailableException("tutor mooves are not available in gen 1");
        } elseif ($moveType === MoveSetHelper::TUTOR_TYPE && $generation === 2 && $versionGroupName !== 'Pokémon Cristal') {
            throw new NotAvailableException("tutor mooves are only available in crystal version for gen 2");
        } elseif ($moveType === MoveSetHelper::TUTOR_TYPE && $generation === 2 && $versionGroupName === 'Pokémon Cristal') {
            return $sections["Capacités apprises//Par Donneur de capacités//Pokémon Cristal"];
        } elseif ($moveType === MoveSetHelper::TUTOR_TYPE && $generation === 3 && $versionGroupName === 'Pokémon Rubis et Saphir') {
            throw new NotAvailableException("tutor mooves are not available in ruby/sapphir");
        } elseif ($moveType === MoveSetHelper::TUTOR_TYPE && $generation >= 3 && $generation <= 6) {
            return $sections[sprintf("Capacités apprises//Par Donneur de capacités//%s", $versionGroupName)];
        } elseif ($moveType === MoveSetHelper::TUTOR_TYPE && $generation === 7) {
            return $sections["Capacités apprises//Par Donneur de capacités//Septième génération"];
        } elseif ($moveType === MoveSetHelper::TUTOR_TYPE && $generation === 8) {
            return $sections["Capacités apprises//Par Donneur de capacités//Huitième génération"];
        }

        throw new NotImplementedException(sprintf("Pokepedia combo %s // %s // %s not implemented", $moveType, $generation, $versionGroupName));
    }

}
