<?php


namespace App\DataFixtures;


use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Entity\VersionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadAvailabilitiesAndMoveForm extends Fixture implements DependentFixtureInterface, AppFixtureInterface
{
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

    private function init(ObjectManager $em): void
    {
        $versiongroupRepository = $em->getRepository(VersionGroup::class);

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

    public function load(ObjectManager $manager): void
    {
        $this->handleAvailablities($manager);
    }

    public function handleAvailablities(ObjectManager $em): void
    {
        $this->init($em);

        $this->loadGens($em);
        $em->flush();
    }

    private function loadGens(ObjectManager $em): void
    {
        $this->handleGen1($em);
        $this->handleGen2($em);
        $this->handleGen3($em);
        $this->handleGen4($em);
        $this->handleGen5($em);
        $this->handleGen6($em);
        $this->handleGen7($em);
        $this->handleLGPE($em);
    }

    private function handleGen1(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->redBlueVg;
        $versionGroups[] = $this->yellowVG;

        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 151);

        $this->saveAvailabilities($em,$pokemons, $versionGroups);
    }

    private function handleGen2(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->crystalVG;
        $versionGroups[] = $this->goldSilverVG;

        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 251);

        $this->saveAvailabilities($em,$pokemons, $versionGroups);
    }

    private function handleGen3(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->emeraldVG;
        $versionGroups[] = $this->rubySapphirVG;
        $versionGroups[] = $this->fireRedLeafGreenVG;

        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 386);

        $this->saveAvailabilities($em,$pokemons, $versionGroups);
    }

    private function handleGen4(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->diamondPearlVG;
        $versionGroups[] = $this->platinumVG;
        $versionGroups[] = $this->heartGoldSoulSilverVG;

        $wormadan = $this->getPokemon($em,'wormadam-plant');
        $sandyWormadan = $this->getPokemon($em,'wormadam-sandy');
        $trashWormadan = $this->getPokemon($em,'wormadam-trash');
        $wormadan->setHasMoveForms(true);
        $wormadan->addMoveForm($sandyWormadan);
        $wormadan->addMoveForm($trashWormadan);
        $em->persist($wormadan);
        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 493);
        array_push($this->formPokemons, $sandyWormadan, $trashWormadan);
        array_push($pokemons, $sandyWormadan, $trashWormadan);
        $this->saveAvailabilities($em,$pokemons, $versionGroups);

        $shaymin = $this->getPokemon($em,'shaymin-land');
        $shayminSky = $this->getPokemon($em,'shaymin-sky');
        $this->formPokemons[] = $shayminSky;

        $shaymin->setHasMoveForms(true);
        $shaymin->addMoveForm($shayminSky);
        $em->persist($shaymin);

        $specificVg = [];
        $specificVg[] = $this->platinumVG;
        $specificVg[] = $this->heartGoldSoulSilverVG;

        $this->saveAvailabilities($em,[$shayminSky], $specificVg);
    }

    private function handleGen5(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->blackWhiteVG;
        $versionGroups[] = $this->black2White2VG;

        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 649);

        $darmatitan = $this->getPokemon($em,'darmanitan-standard');
        $darmanitanZen = $this->getPokemon($em,'darmanitan-zen');
        $this->formPokemons[] = $darmanitanZen;
        $darmatitan->addMoveForm($darmanitanZen);
        $darmatitan->setHasMoveForms(true);
        $em->persist($darmatitan);

        $pokemons = array_merge($this->formPokemons, $pokemons);

        $this->saveAvailabilities($em,$pokemons, $versionGroups);

        $kyurem = $this->getPokemon($em,'kyurem');
        $whiteKyurem = $this->getPokemon($em,'kyurem-black');
        $blackKyurem = $this->getPokemon($em,'kyurem-white');
        array_push($this->formPokemons, $whiteKyurem, $blackKyurem);

        $kyurem->setHasMoveForms(true);
        $kyurem->addMoveForm($whiteKyurem);
        $kyurem->addMoveForm($blackKyurem);
        $em->persist($kyurem);

        $specificVg = [];
        $specificVg[] = $this->black2White2VG;

        $this->saveAvailabilities($em,[$whiteKyurem, $blackKyurem], $specificVg);
    }

    private function handleGen6(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->xyVG;
        $versionGroups[] = $this->orasVG;

        $hoopa = $this->getPokemon($em,'hoopa');
        $hoopaUnbound = $this->getPokemon($em,'hoopa-unbound');
        $this->formPokemons[] = $hoopaUnbound;
        $hoopa->addMoveForm($hoopaUnbound);
        $hoopa->setHasMoveForms(true);
        $em->persist($hoopa);

        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 721);
        $pokemons = array_merge($this->formPokemons, $pokemons);
        $this->saveAvailabilities($em,$pokemons, $versionGroups);
    }

    private function handleGen7(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->sunMoonVG;
        $versionGroups[] = $this->ultraSunUltraMoonVG;

        $lycanroc = $this->getPokemon($em,'lycanroc-midday');
        $lycanrocMidnight = $this->getPokemon($em,'lycanroc-midnight');
        $lycanrocDusk = $this->getPokemon($em,'lycanroc-dusk');
        $this->formPokemons[] = $lycanrocMidnight;

        $lycanroc->addMoveForm($lycanrocMidnight);
        $lycanroc->addMoveForm($lycanrocDusk);
        $lycanroc->setHasMoveForms(true);
        $em->persist($lycanroc);

        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 809);
        $pokemons = array_merge($this->formPokemons, $pokemons);
        $pokemons = array_merge($pokemons, $em->getRepository(Pokemon::class)->findAlolaPokemons());

        $this->saveAvailabilities($em,$pokemons, $versionGroups);

        $this->formPokemons[] = $lycanrocDusk;

        $necrozma = $this->getPokemon($em, 'necrozma');
        $duskNecrozma = $this->getPokemon($em, 'necrozma-dusk');
        $dawnNecrozma = $this->getPokemon($em, 'necrozma-dawn');
        $ultraNecrozma = $this->getPokemon($em, 'necrozma-ultra');
        array_push($this->formPokemons, $duskNecrozma, $dawnNecrozma, $ultraNecrozma, $lycanrocDusk);

        $necrozma->setHasMoveForms(true);
        $necrozma->addMoveForm($duskNecrozma);
        $necrozma->addMoveForm($dawnNecrozma);
        $necrozma->addMoveForm($ultraNecrozma);

        $specificVg = [];
        $specificVg[] = $this->ultraSunUltraMoonVG;

        $this->saveAvailabilities($em,[$duskNecrozma, $dawnNecrozma, $ultraNecrozma, $lycanrocDusk], $specificVg);
        $this->loadAlolaForm($em);

        $em->persist($necrozma);
    }

    private function handleLGPE(ObjectManager $em): void
    {
        $versionGroups = [];
        $versionGroups[] = $this->lgpeVG;

        $pokemons = $em->getRepository(Pokemon::class)
                             ->findDefaultPokemons(1, 151);
        $pokemons = array_merge($pokemons, $em->getRepository(Pokemon::class)->findAlolaPokemons());
        $pokemons[] = $this->getPokemon($em,'meltan');
        $pokemons[] = $this->getPokemon($em, 'melmetal');
        $this->saveAvailabilities($em,$pokemons, $versionGroups);
    }

    private function handleGen8(ObjectManager $em): void
    {
        $this->loadGalarForm($em);
    }

    private function getPokemon(ObjectManager $em,string $name): Pokemon
    {
        return $em->getRepository(Pokemon::class)->findOneBy(['name' => $name]);
    }

    private function saveAvailabilities(ObjectManager $em,array $pokemons, array $versionGroups): void
    {
        foreach ($pokemons as $pokemon) {
            foreach ($versionGroups as $versionGroup) {
                $pokemonAvailability = new PokemonAvailability();
                $pokemonAvailability->setVersionGroup($versionGroup);
                $pokemonAvailability->setPokemon($pokemon);
                $pokemonAvailability->setAvailable(true);
                $em->persist($pokemonAvailability);
            }
        }
    }

    private function loadAlolaForm(ObjectManager $em): void
    {
        $alolaPokemons = $em->getRepository(Pokemon::class)
                                  ->findAlolaPokemons();

        /** @var Pokemon $alolaPokemon */
        foreach ($alolaPokemons as $alolaPokemon) {
            $originalName = str_replace('-alola', '', $alolaPokemon->getName());

            if ($alolaPokemon->getName() === 'raticate-totem-alola') {
                $originalName = 'raticate';
            }

            $original = $em->getRepository(Pokemon::class)
                                 ->findOneBy(['name' => $originalName]);
            $original->setHasMoveForms(true);
            $original->addMoveForm($alolaPokemon);

            $em->persist($original);
        }
    }

    private function loadGalarForm(ObjectManager $em): void
    {
        $alolaPokemons = $em->getRepository(Pokemon::class)
                                  ->findAlolaPokemons();

        /** @var Pokemon $alolaPokemon */
        foreach ($alolaPokemons as $alolaPokemon) {
            $original = $em->getRepository(Pokemon::class)
                                 ->findOneBy(['name' => str_replace('-galar', '', $alolaPokemon->getName())]);
            $original->setHasMoveForms(true);
            $original->addMoveForm($alolaPokemon);

            $em->persist($original);
        }
    }

    public function getDependencies(): array
    {
        return [LoadVersionGroup::class, LoadPokemon::class];
    }
}


