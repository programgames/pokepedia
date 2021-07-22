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
use Symfony\Component\Console\Style\SymfonyStyle;

class BulbapediaMoveProcessor
{
    private EntityManagerInterface $em;
    private BulbapediaMovesAPI $api;

    public function __construct(EntityManagerInterface $em, BulbapediaMovesAPI $api)
    {
        $this->em = $em;
        $this->api = $api;
    }

    public function importMoveByGeneration(int $generation, SymfonyStyle $io, bool $lgpe = false)
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

        $moveMapper = new MoveMapper();

        foreach ($this->em->getRepository(MoveLearnMethod::class)->findPokepediaLearnMethod() as $learnMethod) {
            foreach ($availabilities as $availability) {
                if (!$availability->isAvailable()) {
                    continue;
                }
                $pokemon = $availability->getPokemon();

                $io->info(sprintf('import levelup moves for GEN 8  %s', $pokemon->getName()));
                $moves = $this->getMovesByLearnMethod($pokemon, $generationEntity, $learnMethod);
                if (array_key_exists('noform', $moves)) {
                    foreach ($moves['noform'] as $move) {
                        if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                            $moveMapper->mapMoves($pokemon, $move, $generationEntity, $this->em, $learnMethod);
                        } else {
                            throw new \RuntimeException('Format roman');
                        }
                    }
//                $this->em->flush();
                } else {
                    $this->handleForms($pokemon, $moves, $generationEntity, $this->em, $learnMethod);
                }
            }
        }
    }

    private function getMovesByLearnMethod(Pokemon $pokemon, Generation $generation, MoveLearnMethod $learnMethod, bool $lgpe = false)
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

        throw new \RuntimeException(sprintf('Unhandled learn method import %s', $learnMethod->getName()));
    }

    private function handleForms(
        Pokemon $pokemon,
        array $moves,
        Generation $generationEntity,
        EntityManagerInterface $em,
        $learnMethod
    )
    {
        $moveMapper = new MoveMapper();

        foreach ($moves as $form => $formMoves) {
            if ($pokemon->getName() === strtolower($form)) {
                foreach ($formMoves as $move) {
                    if ($move['format'] === MoveSetHelper::BULBAPEDIA_MOVE_TYPE_GLOBAL) {
                        $moveMapper->mapMoves($pokemon, $move, $generationEntity, $this->em, $learnMethod);
                    } else {
                        throw new \RuntimeException('Format roman');
                    }
                }
            }
        }
    }
}