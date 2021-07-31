<?php


namespace App\Handler;

use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

/** class generating in which version pokemon are available */
class AvailabilityByGenerationHandler
{
    private bool $initialized = false;
    private EntityManagerInterface $em;
    /**
     * @var VersionGroup|object|null
     */
    private $yellowVG;
    /**
     * @var VersionGroup|object|null
     */
    private $redBlueVg;
    /**
     * @var VersionGroup|object|null
     */
    private $goldSilverVG;
    /**
     * @var VersionGroup|object|null
     */
    private $crystalVG;
    /**
     * @var VersionGroup|object|null
     */
    private $rubySapphirVG;
    /**
     * @var VersionGroup|object|null
     */
    private $emeraldVG;
    /**
     * @var VersionGroup|object|null
     */
    private $fireRedLeafGreenVG;
    /**
     * @var VersionGroup|object|null
     */
    private $platinumVG;
    /**
     * @var VersionGroup|object|null
     */
    private $diamondPearlVG;
    /**
     * @var VersionGroup|object|null
     */
    private $sunMoonVG;
    /**
     * @var VersionGroup|object|null
     */
    private $blackWhiteVG;
    /**
     * @var VersionGroup|object|null
     */
    private $black2White2VG;
    /**
     * @var VersionGroup|object|null
     */
    private $xyVG;
    /**
     * @var VersionGroup|object|null
     */
    private $orasVG;
    /**
     * @var VersionGroup|object|null
     */
    private $ultraSunUltraMoonVG;
    /**
     * @var VersionGroup|object|null
     */
    private $lgpeVG;
    /**
     * @var VersionGroup|object|null
     */
    private $heartGoldSoulSilverVG;
    /**
     * @var VersionGroup|object|null
     */
    private $swordShieldVG;

    private array $formPokemons = [];

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function init(): void
    {
        $versiongroupRepository = $this->em->getRepository(VersionGroup::class);

        $this->redBlueVg = $versiongroupRepository->findOneBy([
            'name' => 'red-blue'
        ]);
        $this->yellowVG = $versiongroupRepository->findOneBy([
            'name' => 'yellow'
        ]);
        $this->goldSilverVG = $versiongroupRepository->findOneBy([
            'name' => 'gold-silver'
        ]);
        $this->crystalVG = $versiongroupRepository->findOneBy([
            'name' => 'crystal'
        ]);
        $this->rubySapphirVG = $versiongroupRepository->findOneBy([
            'name' => 'ruby-sapphire'
        ]);
        $this->emeraldVG = $versiongroupRepository->findOneBy([
            'name' => 'emerald'
        ]);
        $this->fireRedLeafGreenVG = $versiongroupRepository->findOneBy([
            'name' => 'firered-leafgreen'
        ]);
        $this->diamondPearlVG = $versiongroupRepository->findOneBy([
            'name' => 'diamond-pearl'
        ]);
        $this->platinumVG = $versiongroupRepository->findOneBy([
            'name' => 'platinum'
        ]);
        $this->heartGoldSoulSilverVG = $versiongroupRepository->findOneBy([
            'name' => 'heartgold-soulsilver'
        ]);
        $this->blackWhiteVG = $versiongroupRepository->findOneBy([
            'name' => 'black-white'
        ]);
        $this->black2White2VG = $versiongroupRepository->findOneBy([
            'name' => 'black-2-white-2'
        ]);
        $this->xyVG = $versiongroupRepository->findOneBy([
            'name' => 'x-y'
        ]);
        $this->orasVG = $versiongroupRepository->findOneBy([
            'name' => 'omega-ruby-alpha-sapphire'
        ]);
        $this->sunMoonVG = $versiongroupRepository->findOneBy([
            'name' => 'sun-moon'
        ]);
        $this->ultraSunUltraMoonVG = $versiongroupRepository->findOneBy([
            'name' => 'ultra-sun-ultra-moon'
        ]);
        $this->lgpeVG = $versiongroupRepository->findOneBy([
            'name' => 'lets-go'
        ]);
        $this->swordShieldVG = $versiongroupRepository->findOneBy([
            'name' => 'sword-shield'
        ]);
    }

    public function handleAvailablities(): void
    {
        if (!$this->initialized) {
            $this->init();
        }

        $this->handleByGen();
        $this->em->flush();
    }

    private function handleByGen(): void
    {
        $this->handleGen1();
        $this->handleGen2();
        $this->handleGen3();
        $this->handleGen4();
        $this->handleGen5();
        $this->handleGen6();
        $this->handleGen7();
        $this->handleLGPE();
    }

    private function handleGen1()
    {
        $versionGroups = [];
        $versionGroups[] = $this->redBlueVg;
        $versionGroups[] = $this->yellowVG;

        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 151);

        $this->saveAvailabilities($pokemons, $versionGroups);
    }

    private function handleGen2()
    {
        $versionGroups = [];
        $versionGroups[] = $this->crystalVG;
        $versionGroups[] = $this->goldSilverVG;

        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 251);

        $this->saveAvailabilities($pokemons, $versionGroups);
    }

    private function handleGen3()
    {
        $versionGroups = [];
        $versionGroups[] = $this->emeraldVG;
        $versionGroups[] = $this->rubySapphirVG;
        $versionGroups[] = $this->fireRedLeafGreenVG;

        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 386);

        $this->saveAvailabilities($pokemons, $versionGroups);
    }

    private function handleGen4()
    {
        $versionGroups = [];
        $versionGroups[] = $this->diamondPearlVG;
        $versionGroups[] = $this->platinumVG;
        $versionGroups[] = $this->heartGoldSoulSilverVG;

        $wormadan = $this->getPokemon('wormadam-plant');
        $sandyWormadan = $this->getPokemon('wormadam-sandy');
        $trashWormadan = $this->getPokemon('wormadam-trash');
        $wormadan->setHasMoveForms(true);
        $wormadan->addForm($sandyWormadan);
        $wormadan->addForm($trashWormadan);
        $this->em->persist($wormadan);
        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 493);
        array_push($this->formPokemons, $sandyWormadan, $trashWormadan);
        array_push($pokemons, $sandyWormadan, $trashWormadan);
        $this->saveAvailabilities($pokemons, $versionGroups);

        $shaymin = $this->getPokemon('shaymin-land');
        $shayminSky = $this->getPokemon('shaymin-sky');
        $this->formPokemons[] = $shayminSky;

        $shaymin->setHasMoveForms(true);
        $shaymin->addForm($shayminSky);
        $this->em->persist($shaymin);

        $specificVg = [];
        $specificVg[] = $this->platinumVG;
        $specificVg[] = $this->heartGoldSoulSilverVG;

        $this->saveAvailabilities([$shayminSky], $specificVg);
    }

    private function handleGen5()
    {
        $versionGroups = [];
        $versionGroups[] = $this->blackWhiteVG;
        $versionGroups[] = $this->black2White2VG;

        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 649);

        $darmatitan = $this->getPokemon('darmanitan-standard');
        $darmanitanZen = $this->getPokemon('darmanitan-zen');
        $this->formPokemons[] = $darmanitanZen;
        $darmatitan->addForm($darmanitanZen);
        $darmatitan->setHasMoveForms(true);
        $this->em->persist($darmatitan);

        $pokemons = array_merge($this->formPokemons, $pokemons);

        $this->saveAvailabilities($pokemons, $versionGroups);

        $kyurem = $this->getPokemon('kyurem');
        $whiteKyurem = $this->getPokemon('kyurem-black');
        $blackKyurem = $this->getPokemon('kyurem-white');
        array_push($this->formPokemons, $whiteKyurem, $blackKyurem);

        $kyurem->setHasMoveForms(true);
        $kyurem->addForm($whiteKyurem);
        $kyurem->addForm($blackKyurem);
        $this->em->persist($kyurem);

        $specificVg = [];
        $specificVg[] = $this->black2White2VG;

        $this->saveAvailabilities([$whiteKyurem, $blackKyurem], $specificVg);
    }

    private function handleGen6()
    {
        $versionGroups = [];
        $versionGroups[] = $this->xyVG;
        $versionGroups[] = $this->orasVG;

        $hoopa = $this->getPokemon('hoopa');
        $hoopaUnbound = $this->getPokemon('hoopa-unbound');
        $this->formPokemons[] = $hoopaUnbound;
        $hoopa->addForm($hoopaUnbound);
        $hoopa->setHasMoveForms(true);
        $this->em->persist($hoopa);

        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 721);
        $pokemons = array_merge($this->formPokemons, $pokemons);
        $this->saveAvailabilities($pokemons, $versionGroups);
    }

    private function handleGen7()
    {
        $versionGroups = [];
        $versionGroups[] = $this->sunMoonVG;
        $versionGroups[] = $this->ultraSunUltraMoonVG;

        $lycanroc = $this->getPokemon('lycanroc-midday');
        $lycanrocMidnight = $this->getPokemon('lycanroc-midnight');
        $lycanrocDusk = $this->getPokemon('lycanroc-dusk');
        $this->formPokemons[] = $lycanrocMidnight;

        $lycanroc->addForm($lycanrocMidnight);
        $lycanroc->addForm($lycanrocDusk);
        $lycanroc->setHasMoveForms(true);
        $this->em->persist($lycanroc);

        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 809);
        $pokemons = array_merge($this->formPokemons, $pokemons);
        $pokemons = array_merge($pokemons, $this->em->getRepository(Pokemon::class)->findAlolaPokemons());

        $this->saveAvailabilities($pokemons, $versionGroups);

        $this->formPokemons[] = $lycanrocDusk;

        $necrozma = $this->getPokemon('necrozma');
        $duskNecrozma = $this->getPokemon('necrozma-dusk');
        $dawnNecrozma = $this->getPokemon('necrozma-dawn');
        $ultraNecrozma = $this->getPokemon('necrozma-ultra');
        array_push($this->formPokemons, $duskNecrozma, $dawnNecrozma, $ultraNecrozma, $lycanrocDusk);

        $necrozma->setHasMoveForms(true);
        $necrozma->addForm($duskNecrozma);
        $necrozma->addForm($dawnNecrozma);
        $necrozma->addForm($ultraNecrozma);

        $specificVg = [];
        $specificVg[] = $this->ultraSunUltraMoonVG;

        $this->saveAvailabilities([$duskNecrozma, $dawnNecrozma, $ultraNecrozma, $lycanrocDusk], $specificVg);
        $this->loadAlolaForm();

        $this->em->persist($necrozma);
    }

    private function handleLGPE()
    {
        $versionGroups = [];
        $versionGroups[] = $this->lgpeVG;

        $pokemons = $this->em->getRepository(Pokemon::class)
            ->findDefaultPokemons(1, 151);
        $pokemons = array_merge($pokemons, $this->em->getRepository(Pokemon::class)->findAlolaPokemons());
        $pokemons[] = $this->getPokemon('meltan');
        $pokemons[] = $this->getPokemon('melmetal');
        $this->saveAvailabilities($pokemons, $versionGroups);
    }

    private function handleGen8()
    {
        $this->loadGalarForm();
    }

    private function getPokemon(string $name): Pokemon
    {
        return $this->em->getRepository(Pokemon::class)->findOneBy(['name' => $name]);
    }

    private function saveAvailabilities(array $pokemons, array $versionGroups)
    {
        foreach ($pokemons as $pokemon) {
            foreach ($versionGroups as $versionGroup) {
                $pokemonAvailability = new PokemonAvailability();
                $pokemonAvailability->setVersionGroup($versionGroup);
                $pokemonAvailability->setPokemon($pokemon);
                $pokemonAvailability->setAvailable(true);
                $this->em->persist($pokemonAvailability);
            }
        }
    }

    private function loadAlolaForm()
    {
        $alolaPokemons = $this->em->getRepository(Pokemon::class)
            ->findAlolaPokemons();

        /** @var Pokemon $alolaPokemon */
        foreach ($alolaPokemons as $alolaPokemon) {
            $originalName = str_replace('-alola', '', $alolaPokemon->getName());

            if ($alolaPokemon->getName() === 'raticate-totem-alola') {
                $originalName = 'raticate';
            }

            $original = $this->em->getRepository(Pokemon::class)
                ->findOneBy(['name' => $originalName]);
            $original->setHasMoveForms(true);
            $original->addForm($alolaPokemon);

            $this->em->persist($original);
        }
    }

    private function loadGalarForm()
    {
        $alolaPokemons = $this->em->getRepository(Pokemon::class)
            ->findAlolaPokemons();

        /** @var Pokemon $alolaPokemon */
        foreach ($alolaPokemons as $alolaPokemon) {
            $original = $this->em->getRepository(Pokemon::class)
                ->findOneBy(['name' => str_replace('-galar', '', $alolaPokemon->getName())]);
            $original->setHasMoveForms(true);
            $original->addForm($alolaPokemon);

            $this->em->persist($original);
        }
    }
}
