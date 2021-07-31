<?php


namespace App\Processor;

use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Entity\VersionGroup;
use App\Helper\MoveSetHelper;
use App\MoveMapper;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Component\Console\Style\SymfonyStyle;

/** Class responsible of importing bulbapedia pokemon moves */
class BulbapediaMoveProcessor
{
    private EntityManagerInterface $em;
    private BulbapediaMovesAPI $api;

    public function __construct(EntityManagerInterface $em, BulbapediaMovesAPI $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    public function importMoveByGeneration(int $generation, SymfonyStyle $io, bool $lgpe = false): void
    {
        $generationEntity = $this->em->getRepository(Generation::class)->findOneBy(
            [
                'generationIdentifier' => $generation
            ]
        );

        $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
            [
                'generation' => $generationEntity,
            ]
        );

        $availabilities = $this->em->getRepository(PokemonAvailability::class)->findBy(
            [
                'versionGroup' => $versionGroup,
            ]
        );

        foreach ($this->em->getRepository(MoveLearnMethod::class)->findPokepediaLearnMethod() as $learnMethod) {
            if ($learnMethod->getName() === 'level-up') {
                continue;
            }
            foreach ($availabilities as $availability) {
                if (!$availability->isAvailable()) {
                    continue;
                }
                $pokemon = $availability->getPokemon();
                if ($pokemon->getIsAlola() || $pokemon->getIsGalar()) {
                    continue;
                }

                $io->info(sprintf('import %s moves for GEN 8  %s', $learnMethod->getName(), $pokemon->getName()));
                $moves = $this->getMovesByLearnMethod($pokemon, $generationEntity, $learnMethod);
                if (array_key_exists('noform', $moves)) {
                    foreach ($moves['noform'] as $move) {
                        $this->handleMoveByFormat($pokemon, $move, $generationEntity, $this->em, $learnMethod);
                    }
//                $this->em->flush();
                } else {
                    $this->handleForms($pokemon, $moves, $generationEntity, $this->em, $learnMethod);
                }
            }
        }
    }

    private function getMovesByLearnMethod(
        Pokemon $pokemon,
        Generation $generation,
        MoveLearnMethod $learnMethod,
        bool $lgpe = false
    ): array {
        if ($learnMethod->getName() === 'level-up') {
            return $this->api->getLevelMoves($pokemon, $generation->getGenerationIdentifier(), $lgpe);
        }

        if ($learnMethod->getName() === 'tutor') {
            return $this->api->getTutorMoves($pokemon, $generation->getGenerationIdentifier(), $lgpe);
        }

        if ($learnMethod->getName() === 'machine') {
            return $this->api->getMachineMoves($pokemon, $generation->getGenerationIdentifier(), $lgpe);
        }

        if ($learnMethod->getName() === 'egg') {
            return $this->api->getEggMoves($pokemon, $generation->getGenerationIdentifier(), $lgpe);
        }

        throw new RuntimeException(sprintf('Unhandled learn method import %s', $learnMethod->getName()));
    }

    private function handleForms(
        Pokemon $pokemon,
        array $moves,
        Generation $generationEntity,
        EntityManagerInterface $em,
        $learnMethod
    ): void {
        foreach ($moves as $form => $formMoves) {
            $form = $this->formatForm($form);
            if ($pokemon->getName() === $form) {
                foreach ($formMoves as $move) {
                    $this->handleMoveByFormat($pokemon, $move, $generationEntity, $em, $learnMethod);
                }
            } elseif (false !== strpos($form, "alolan")) {
                $pokemonForm = $this->em->getRepository(Pokemon::class)->findOneBy(
                    [
                        'name' => $pokemon->getName() . '-alola'
                    ]
                );
                foreach ($formMoves as $move) {
                    $this->handleMoveByFormat($pokemonForm, $move, $generationEntity, $em, $learnMethod);
                }
            } elseif (false !== strpos($form, "galarian")) {
                $pokemonForm = $this->em->getRepository(Pokemon::class)->findOneBy(
                    [
                        'name' => $pokemon->getName() . '-galar'
                    ]
                );
                foreach ($formMoves as $move) {
                    $this->handleMoveByFormat($pokemonForm, $move, $generationEntity, $em, $learnMethod);
                }
            } else {
                $pokemonForm = $this->em->getRepository(Pokemon::class)->findOneBy(
                    [
                        'name' => $form
                    ]
                );
                if ($pokemonForm) {
                    foreach ($formMoves as $move) {
                        $this->handleMoveByFormat($pokemonForm, $move, $generationEntity, $em, $learnMethod);
                    }
                } else {
                    throw new RuntimeException('Unknown form');
                }
            }
        }
    }

    private function handleMoveByFormat(
        Pokemon $pokemon,
        $move,
        $generationEntity,
        EntityManagerInterface $em,
        $learnMethod
    ): void {
        $moveMapper = new MoveMapper();

        if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
            $moveMapper->mapMoves($pokemon, $move, $generationEntity, $this->em, $learnMethod);
        } else {
            throw new RuntimeException('Format roman');
        }
    }

    private function formatForm(string $form)
    {
        $form = str_replace(array('\'', '. '), array('', '-'), strtolower($form));

        if ($form === 'darmanitan') {
            $form = 'darmanitan-standard';
        } elseif ($form === 'galarian darmanitan') {
            $form = 'darmanitan-standard';
        } elseif ($form === 'white kyurem') {
            $form = 'kyurem-white';
        } elseif ($form === 'black kyurem') {
            $form = 'kyurem-black';
        } elseif ($form === 'male meowstic') {
            $form = 'meowstic-male';
        } elseif ($form === 'female meowstic') {
            $form = 'meowstic-female';
        } elseif ($form === 'midday form') {
            $form = 'lycanroc-midday';
        } elseif ($form === 'midnight form') {
            $form = 'lycanroc-midnight';
        } elseif ($form === 'dusk form') {
            $form = 'lycanroc-dusk';
        } elseif ($form === 'amped form') {
            $form = 'toxtricity-amped';
        } elseif ($form === 'low key form') {
            $form = 'toxtricity-low-key';
        } elseif ($form === 'male indeedee') {
            $form = 'indeedee-male';
        } elseif ($form === 'female indeedee') {
            $form = 'indeedee-female';
        } elseif ($form === 'single strike style') {
            $form = 'urshifu-single-strike';
        } elseif ($form === 'rapid strike style') {
            $form = 'urshifu-rapid-strike';
        } elseif ($form === 'ice rider calyrex') {
            $form = 'calyrex-ice-rider';
        } elseif ($form === 'shadow rider calyrex') {
            $form = 'calyrex-shadow-rider';
        }
        //wormadam-plant;shaymin-land
        return $form;
    }
}
