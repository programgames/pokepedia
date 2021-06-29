<?php


namespace App\Api\Bulbapedia;


use App\Entity\Pokemon;
use App\Entity\PokemonName;
use App\Exception\EmptyMoveSetException;
use App\Exception\WrongHeaderException;
use App\MoveSet\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\ItemInterface;

class BulbapediaMovesAPI
{
    private EntityManagerInterface $entityManager;
    private FilesystemAdapter $cache;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
            $move = str_replace(array('{', '}'), '', $move);
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

    public function getLevelMoves(Pokemon $pokemon, string $generation)
    {
        $moves = $this->cache->get(sprintf('wikitext.%s,%s.%s', $pokemon->getId(), $generation, MoveSetHelper::LEVELING_UP_TYPE), function (ItemInterface $item) use ($pokemon, $generation) {

            return $this->getMovesByPokemonGenerationAndType($pokemon, $generation, MoveSetHelper::BULBAPEDIA_LEVELING_UP_TYPE_LABEL);
        });

        $moveNames = [];

        $this->checkLevelingFormat($moves);

        foreach ($moves as $move) {
            $move = str_replace(array('{', '}'), '', $move);

            if (preg_match('/level\dnull/', $move, $matches)) {
                throw new EmptyMoveSetException(sprintf('Empty moveset for pokemon %s in gen %s', $pokemon->getPokemonIdentifier(), $generation));
            }

            if (preg_match('/level\d+.*/', $move, $matches)) {
                $moveNames[] = [
                    'format' => 'numeral',
                    'value' => explode('|', $move),
                    'gen' => $generation
                ];
            }
            if (preg_match('/level[XVI]+.*/', $move, $matches)) {
                $moveNames[] = [
                    'format' => 'roman',
                    'value' => explode('|', $move),
                    'gen' => $generation
                ];
            }

        }

        if (empty($moveNames)) {
            throw new EmptyMoveSetException(sprintf('Empty moveset for pokemon %s in gen %s', $pokemon->getPokemonIdentifier(), $generation));
        }

        return $moveNames;
    }

    private function getMoveSections(Pokemon $pokemon, string $generation)
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
                '%generation%' => $generation,
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

    private function getMovesByPokemonGenerationAndType(Pokemon $pokemon, string $generation, string $moveType): array
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

    private function checkLevelingFormat(array $moves)
    {
        if ($moves[0] !== '====By [[Level|leveling up]]====') {
            throw  new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        };

        if (!preg_match('/{{learnlist\/levelh.*}}/', $moves[1], $matches)) {
            throw  new WrongHeaderException(sprintf('Invalid header: %s', $moves[1]));
        }
    }

    private function checkTutoringFormat(array $moves)
    {
        if ($moves[0] !== '====By [[Move Tutor|tutoring]]====') {
            throw  new WrongHeaderException(sprintf('Invalid header: %s', $moves[0]));
        };

        if (!preg_match('/\{\{learnlist\/tutorh.*}}/', $moves[1], $matches)) {
            throw  new WrongHeaderException(sprintf('Invalid header: %s', $moves[1]));
        }
    }
}
