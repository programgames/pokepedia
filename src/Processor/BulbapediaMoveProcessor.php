<?php

namespace App\Processor;

use App\Api\Bulbapedia\BulbapediaMovesAPI;
use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonFormName;
use App\Entity\PokemonMoveAvailability;
use App\Entity\SpecyName;
use App\Entity\VersionGroup;
use App\Helper\MoveSetHelper;
use App\Helper\PokemonHelper;
use App\MoveMapper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Style\SymfonyStyle;

/** Class responsible of importing bulbapedia pokemon moves */
class BulbapediaMoveProcessor
{
    private EntityManagerInterface $em;
    private BulbapediaMovesAPI $api;
    /**
     * @var PokemonHelper
     */
    private PokemonHelper $pokemonHelper;

    public function __construct(EntityManagerInterface $em, BulbapediaMovesAPI $api, PokemonHelper $pokemonHelper)
    {
        $this->em = $em;
        $this->api = $api;
        $this->pokemonHelper = $pokemonHelper;
    }

    /**
     * @param int $generation
     * @param SymfonyStyle $io
     * @param bool $lgpe
     * @throws InvalidArgumentException
     */
    public function importMoveByGeneration(int $generation, SymfonyStyle $io, bool $lgpe = false): void
    {
        $generationEntity = $this->em->getRepository(Generation::class)->findOneBy(
            [
                'generationIdentifier' => $generation
            ]
        );

        $versionGroup = $this->getVersionGroupByGeneration($generationEntity, $lgpe);

        $pokemonMoveAvailabilities = $this->em->getRepository(PokemonMoveAvailability::class)->findBy(['versionGroup' => $versionGroup]);

        foreach ($this->em->getRepository(MoveLearnMethod::class)->findPokepediaLearnMethod() as $learnMethod) {

            foreach ($pokemonMoveAvailabilities as $availability) {

                $pokemon = $availability->getPokemon();
                if ($pokemon->getName() === 'mew' && $learnMethod->getName() === 'machine') {
                    continue;
                }
                if (false !== strpos($pokemon->getName(), "alola") || false !== strpos($pokemon->getName(), "galar") || !$availability->getIsDefault()) {
                    continue;
                }

                $io->info(sprintf('import %s moves for GEN %s  %s', $learnMethod->getName(), $generation, $pokemon->getName()));
                $moves = $this->getMovesByLearnMethod($pokemon, $generationEntity, $learnMethod);
                if (array_key_exists('noform', $moves)) {
                    foreach ($moves['noform'] as $move) {
                        $this->handleMoveByFormat($pokemon, $move, $generationEntity, $this->em, $learnMethod);
                    }
                } else {
                    $this->handleForms($pokemon, $moves, $generationEntity, $this->em, $learnMethod);
                }
                $this->em->flush();
            }
        }
    }

    /**
     * @param Pokemon $pokemon
     * @param Generation $generation
     * @param MoveLearnMethod $learnMethod
     * @param bool $lgpe
     * @return array
     * @throws InvalidArgumentException
     */
    private function getMovesByLearnMethod(
        Pokemon $pokemon,
        Generation $generation,
        MoveLearnMethod $learnMethod,
        bool $lgpe = false
    ): array
    {
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
    ): void
    {
        foreach ($moves as $form => $formMoves) {
            $pokemon = $this->pokemonHelper->findPokemonByFormName($pokemon, $form, 9);
            foreach ($formMoves as $formMove) {
                $this->handleMoveByFormat($pokemon, $formMove, $generationEntity, $this->em, $learnMethod);
            }
        }
    }

    private function handleMoveByFormat(
        Pokemon $pokemon,
        $move,
        $generationEntity,
        EntityManagerInterface $em,
        $learnMethod
    ): void
    {
        $moveMapper = new MoveMapper();

        if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
            $moveMapper->mapMoves($pokemon, $move, $generationEntity, $em, $learnMethod);
        } else {
            //not yet implemented
            throw new RuntimeException('Format roman');
        }
    }

    /** Get version with maximum pokemon available in it
     * @param Generation $generationEntity
     * @param bool $lgpe
     * @return VersionGroup|object|null
     */
    private function getVersionGroupByGeneration(Generation $generationEntity, bool $lgpe = false)
    {
        $gen = $generationEntity->getGenerationIdentifier();
        if ($gen <= 3) {
            $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
                [
                    'generation' => $generationEntity,
                ]
            );
        } elseif ($gen === 4) {
            $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => 'platinum',
                ]
            );
        } elseif ($gen === 5) {
            $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => 'black-2-white-2',
                ]
            );
        } elseif ($gen === 6) {
            $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => 'omega-ruby-alpha-sapphire',
                ]
            );
        } elseif ($gen === 7 && $lgpe) {
            $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => 'lets-go',
                ]
            );
        } elseif ($gen === 7 && !$lgpe) {
            $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => 'lets-go',
                ]
            );
        } else {
            $versionGroup = $this->em->getRepository(VersionGroup::class)->findOneBy(
                [
                    'name' => 'sword-shield',
                ]
            );
        }
        return $versionGroup;
    }
}
