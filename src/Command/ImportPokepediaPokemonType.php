<?php


namespace App\Command;


use App\Api\Pokepedia\PokepediaBasePokemonInformationApi;
use App\Entity\BaseInformation;
use App\Entity\Pokemon;
use App\Helper\MoveSetHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportPokepediaPokemonType extends Command
{
    protected static $defaultName = 'app:pokepedia:types';
    protected static $defaultDescription = 'Import pokepedia pokemon types';

    private PokepediaBasePokemonInformationApi $api;
    private EntityManagerInterface $em;
    private MoveSetHelper $moveSetHelper;

    public function __construct(PokepediaBasePokemonInformationApi $api, EntityManagerInterface $em,MoveSetHelper $helper)
    {
        parent::__construct();
        $this->api = $api;
        $this->em = $em;
        $this->moveSetHelper = $helper;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input,$output);
        $pokemons = $this->em->getRepository(Pokemon::class)->findDefaultAndAlolaPokemons();

        /** @var Pokemon $pokemon */
        foreach ($pokemons as $pokemon) {
            $io->info(sprintf('Importing type 1 for pokemon %s',$pokemon->getName()));
            $info = $pokemon->getBaseInformation() ?? new BaseInformation();
            $info->setType1($this->api->getPokepediaTypeOneName($this->moveSetHelper->getPokepediaPokemonName($pokemon)));
            $pokemon->setBaseInformation($info);
            $this->em->persist($info);
            $this->em->persist($pokemon);
        }
        $this->em->flush();

        return Command::SUCCESS;
    }


}