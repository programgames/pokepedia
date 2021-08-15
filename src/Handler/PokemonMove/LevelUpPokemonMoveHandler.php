<?php


namespace App\Handler\PokemonMove;


use App\Entity\Generation;
use App\Entity\MoveLearnMethod;
use App\Entity\Pokemon;
use App\Entity\PokemonMoveAvailability;
use App\Handler\SynchroHandlerInterface;
use App\Processor\PokemonMoveCompareProcessor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

class LevelUpPokemonMoveHandler implements SynchroHandlerInterface
{
    private PokemonMoveCompareProcessor $processor;
    private EntityManagerInterface $em;

    public function __construct(PokemonMoveCompareProcessor $processor, EntityManagerInterface $em)
    {
        $this->processor = $processor;
        $this->em = $em;
    }

    public function process()
    {
        $pokemons = $this->em->getRepository(PokemonMoveAvailability::class)->findPokemonWithSpecificPageStartingAt(1);
        $pokemons = [$this->em->getRepository(Pokemon::class)->findOneBy(['name' => 'lycanroc-midday'])];

        $learnMethod = $this->em->getRepository(MoveLearnMethod::class)->findOneBy(['name' => 'level-up']);
        $generations = $this->em->getRepository(Generation::class)->findAll();

        foreach ($generations as $generation) {
            foreach ($pokemons as $pokemon) {
                try {
                    $this->processor->process($generation, $learnMethod, $pokemon, false);
                } catch (\Exception $exception) {
                    throw new UnrecoverableMessageHandlingException(sprintf('Error happened for %s generaton %s',
                        $pokemon->getName(),
                        $generation->getGenerationIdentifier(),
                    ),
                        $exception->getCode(),
                        $exception
                    );
                }
            }
        }
    }
}