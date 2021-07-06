<?php


namespace App\Command;


use App\Api\Pokepedia\PokepediaMoveApi;
use App\Entity\Pokemon;
use App\Entity\SpecyName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ComparePokemonMoveCommand extends Command
{
    protected static $defaultName = 'app:compare:pokepedia:moveset';
    protected static $defaultDescription = 'compare movesets';

    private EntityManagerInterface $em;
    private PokepediaMoveApi $api;

    /**
     * ComparePokemonMoveCommand constructor.
     * @param EntityManagerInterface $em
     * @param PokepediaMoveApi $api
     */
    public function __construct(EntityManagerInterface $em, PokepediaMoveApi $api)
    {
        parent::__construct();

        $this->em = $em;
        $this->api = $api;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $pokemon = $this->em->getRepository(Pokemon::class)
            ->findOneBy(
                [
                    'name' => 'bulbasaur'
                ]
            );
        $specyName = $this->em->getRepository(SpecyName::class)
            ->findOneBy(
                [
                    'pokemonSpecy' => $pokemon->getPokemonSpecy(),
                    'language' => 5
                ]
            );

        $this->api->getLevelMoves($specyName->getName(),1);
        return Command::SUCCESS;
    }
}