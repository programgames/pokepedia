<?php


namespace App\Command;


use App\Api\PokeAPI\PokemonMoveApi;
use App\Entity\MoveLearnMethod;
use App\Entity\MoveName;
use App\Entity\Pokemon;
use App\Entity\PokemonMove;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GetFormattedMovesByPokemon extends Command
{
    protected static $defaultName = 'app:format:pokeapi:moveset';
    protected static $defaultDescription = 'format movesets';

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pokemon = $this->em->getRepository(Pokemon::class)->findBy(
            [
                'pokemonOrder' => 1
            ]
        );
        $versionGroup = $this->em->getRepository(VersionGroup::class)->findBy(
            [
                'name' => 'red-blue'
            ]
        );

        $learnMethod = $this->em->getRepository(MoveLearnMethod::class)->findBy(
            [
                'name' => 'level-up'
            ]
        );
        $pokemonMoves = $this->em->getRepository(PokemonMove::class)->findBy(
            [
                'pokemon' => $pokemon,
                'versionGroup' => $versionGroup,
                'learnMethod' => $learnMethod
            ]
        );

        foreach ($pokemonMoves as $pokemonMove) {
            $move = $pokemonMove->getMove();
            $name = $this->em->getRepository(MoveName::class)->findOneBy(
                [
                    'move' => $move,
                    'language' => 5,
                ]
            );
            $names[$pokemonMove->getLevel()] = $name->getName();
        }
        ksort($names);
        return Command::SUCCESS;
    }
}