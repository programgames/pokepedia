<?php


namespace App\Handler;


use App\Entity\Pokemon;
use App\Entity\PokemonAvailability;
use App\Entity\VersionGroup;
use Doctrine\ORM\EntityManagerInterface;

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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function init()
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

    public function handleAvailablities(Pokemon $pokemon, string $generation, array $availabilities)
    {
        if (!$this->initialized) {
            $this->init();
        }

        $this->handleByGen($pokemon, $availabilities, $generation);
    }

    private function handleByGen(Pokemon $pokemon, array $availabilities, $generation)
    {

        if ($generation === 'Unknown Origins Pokémon') {
            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->lgpeVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[1]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->swordShieldVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[1]));
            $this->em->persist($avail);
            return;
        }

        $index = 0;
        switch ($generation) {
            case 'Generation I Pokémon':
                $index = 0;
                break;
            case 'Generation II Pokémon':
                $index = 4;
                break;
            case 'Generation III Pokémon':
                $index = 7;
                break;
            case 'Generation IV Pokémon':
                $index = 14;
                break;
            case 'Generation V Pokémon':
                $index = 20;
                break;
            case 'Generation VI Pokémon':
                $index = 24;
                break;
            case 'Generation VII Pokémon':
                $index = 28;
                break;
            case 'Generation VIII Pokémon':
                $index = 32;
                break;
        }
        $generation = $this->convertGeneration($generation);

        if ($generation <= 1) {
            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->redBlueVg);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[3 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->yellowVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[6 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->lgpeVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[35 - $index]));
            $this->em->persist($avail);
        }

        if ($generation <= 2) {

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->goldSilverVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[7 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->crystalVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[9 - $index]));
            $this->em->persist($avail);
        }

        if ($generation <= 3) {

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->rubySapphirVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[10 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->fireRedLeafGreenVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[12 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->emeraldVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[14 - $index]));
            $this->em->persist($avail);

        }

        if ($generation <= 4) {

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->diamondPearlVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[17 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->platinumVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[19 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->heartGoldSoulSilverVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[20 - $index]));
            $this->em->persist($avail);

        }

        if ($generation <= 5) {

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->blackWhiteVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[23 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->black2White2VG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[25 - $index]));
            $this->em->persist($avail);

        }

        if ($generation <= 6) {

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->xyVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[27 - $index]));
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->orasVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[29 - $index]));
            $this->em->persist($avail);

        }

        if ($generation <= 7) {

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->sunMoonVG);
            $avail->setPokemon($pokemon);
            if ($pokemon->getName() === ('meltan' || 'melmetal')) {
                $avail->setAvailability($this->getAvailability(4));
            } else {
                $avail->setAvailability($this->getAvailability($availabilities[31 - $index]));
            }
            $this->em->persist($avail);

            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->ultraSunUltraMoonVG);
            $avail->setPokemon($pokemon);
            $avail->setAvailability($this->getAvailability($availabilities[33 - $index]));
            $this->em->persist($avail);
        }
        if ($generation <= 8) {
            if ($generation > 1) {
                $index += 2;
            }
            $avail = new PokemonAvailability();
            $avail->setVersionGroup($this->swordShieldVG);
            $avail->setPokemon($pokemon);
            if ($pokemon->getName() === 'meltan' || $pokemon->getName() === 'melmetal') {
                $avail->setAvailability($this->getAvailability(6));
            } else {
                $avail->setAvailability($this->getAvailability($availabilities[37 - $index]));
            }
            $this->em->persist($avail);
        }
    }

    function getAvailability(string $flag)
    {
        $flag = trim($flag);
        return !($flag === '—');
    }

    private function convertGeneration($generation)
    {
        switch ($generation) {
            case 'Generation I Pokémon':
                return 1;
            case 'Generation II Pokémon':
                return 2;

            case 'Generation III Pokémon':
                return 3;

            case 'Generation IV Pokémon':
                return 4;

            case 'Generation V Pokémon':
                return 5;

            case 'Generation VI Pokémon':
                return 6;

            case 'Generation VII Pokémon':
                return 7;

            case 'Generation VIII Pokémon':
                return 8;

        }

    }
}