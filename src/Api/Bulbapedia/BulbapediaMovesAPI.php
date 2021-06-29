<?php


namespace App\Api\Bulbapedia;


use App\Entity\Pokemon;
use App\Entity\PokemonName;
use App\Exception\EmptyMoveSetException;
use App\Exception\WrongHeaderException;
use App\Formatter\MoveFormatter;
use App\Generation\GenerationHelper;
use App\MoveSet\MoveSetHelper;
use App\Sanitize\MoveSatanizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\ItemInterface;

class BulbapediaMovesAPI
{
    private EntityManagerInterface $entityManager;
    private FilesystemAdapter $cache;
    private MoveSatanizer $moveSatanizer;
    private MoveFormatter $moveFormatter;

    public function __construct(EntityManagerInterface $entityManager,MoveSatanizer $moveSatanizer, MoveFormatter $moveFormatter)
    {
        $this->entityManager = $entityManager;
        $this->moveSatanizer  = $moveSatanizer;
        $this->moveFormatter  = $moveFormatter;
        $this->cache = new FilesystemAdapter();
    }

    public function getTutorMoves(Pokemon $pokemon, string $generation)
    {
        $moves = $this->cache->get(sprintf('wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::TUTORING_TYPE), function (ItemInterface $item) use ($pokemon, $generation) {
            return $this->getMovesByPokemonGenerationAndType($pokemon, $generation, MoveSetHelper::BULBAPEDIA_TUTORING_TYPE_LABEL);
        });

        $this->checkTutoringFormat($moves);
        $moveNames = [];

        foreach ($moves as $move) {
            if (!preg_match('/tutor\d/', $move, $matches)) {
                continue;
            }

            $moveNames[] = explode('|', $move);
        }
        if (empty($moveNames)) {
            throw new EmptyMoveSetException(sprintf('Empty moveset for pokemon %s in gen %s', $pokemon->getPokemonIdentifier(), $generation));
        }

        return $moveNames;
    }

    public function getLevelMoves(Pokemon $pokemon, int $generation)
    {
        $moves = $this->cache->get(sprintf('wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::LEVELING_UP_TYPE), function (ItemInterface $item) use ($pokemon, $generation) {

            return $this->getMovesByPokemonGenerationAndType($pokemon, $generation, MoveSetHelper::BULBAPEDIA_LEVELING_UP_TYPE_LABEL);
        });

        $moves = $this->moveSatanizer->checkAndSanitizeLevelingMoves($moves);
        $moves = $this->moveFormatter->formatLevelingLearnlist($moves,$generation);

        if (empty($moves)) {
            throw new EmptyMoveSetException(sprintf('Empty moveset for pokemon %s in gen %s', $pokemon->getPokemonIdentifier(), $generation));
        }

        return $moves;
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
        $sectionsUrl = strtr('https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=sections&errorformat=wikitext&disabletoc=1',
            [
                '%pokemon%' => $pokemonName->getName(),
                '%generation%' => GenerationHelper::genNumberToLitteral($generation),
            ]);

        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $sectionsUrl);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);

        foreach ($json['parse']['sections'] as $section) {
            $formattedSections[$section['line']] = $section['index'];
        }

        return $formattedSections;
    }

    private function getMovesByPokemonGenerationAndType(Pokemon $pokemon, int $generation, string $moveType): array
    {
        $pokemonName = $this->entityManager->getRepository(PokemonName::class)
            ->findOneBy(
                [
                    'pokemon' => $pokemon,
                    'languageId' => 9
                ]
            );

        $sections = $this->getMoveSections($pokemon, $generation);

        $url = strtr('https://bulbapedia.bulbagarden.net/w/api.php?action=parse&format=json&page=%pokemon%_(Pok%C3%A9mon)/Generation_%generation%_learnset&prop=wikitext&errorformat=wikitext&section=%section%&disabletoc=1',
            [
                '%pokemon%' => $pokemonName->getName(),
//                '%pokemon%' => 'Kyurem',
                '%generation%' => GenerationHelper::genNumberToLitteral($generation),
                '%section%' => $sections[$moveType]
            ]);


        $browser = new HttpBrowser(HttpClient::create());
        $browser->request('GET', $url);

        $response = $browser->getResponse();
        $json = json_decode($response->getContent(), true);
        $wikitext = reset($json['parse']['wikitext']);
        return preg_split('/$\R?^/m', $wikitext);
    }

    private function checkTutoringFormat(array $moves)
    {
        $movesSize = count($moves);

        if ($moves[0] !== '====By [[Move Tutor|tutoring]]====') {
            throw  new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        };

        if (!preg_match('/\{\{learnlist\/tutorh.*}}/', $moves[1], $matches)) {
            throw  new WrongHeaderException(sprintf('Invalid header: %s', $moves[1]));
        }
    }
}
