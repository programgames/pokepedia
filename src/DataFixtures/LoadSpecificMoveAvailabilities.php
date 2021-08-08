<?php


namespace App\DataFixtures;


use App\Entity\Pokedex;
use App\Entity\Pokemon;
use App\Entity\PokemonDexNumber;
use App\Entity\PokemonForm;
use App\Entity\PokemonMoveAvailability;
use App\Entity\VersionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadSpecificMoveAvailabilities extends Fixture implements DependentFixtureInterface, AppFixtureInterface
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
        $this->handleGen4($em);
        $this->handleGen5($em);
        $this->handleGen6($em);
        $this->handleGen7($em);
        $this->handleGen8($em);
    }

    private function saveSpecificAvailabilities(ObjectManager $em, array $versionGroups, string $originalName, array $forms, bool $specificPageForForms = false)
    {

        foreach ($versionGroups as $versionGroup) {
            $original = $this->getAvailabilityMove($em, $originalName);
            foreach ($forms as $form) {
                $formPokemon = $this->getPokemon($em, $form);
                $original->addMoveForm($formPokemon);
                $availability = new PokemonMoveAvailability();
                $availability->setVersionGroup($versionGroup);
                $availability->setPokemon($formPokemon);
                $availability->setHasCustomPokepediaPage($specificPageForForms);
                $availability->setHasCustomPokepediaPage($specificPageForForms);
                $availability->setIsDefault(false);
                $em->persist($availability);
            }

            $em->persist($original);
        }
    }

    private function handleGen4(ObjectManager $em): void
    {
        $versionGroups = [$this->diamondPearlVG, $this->platinumVG, $this->heartGoldSoulSilverVG];

        $this->saveSpecificAvailabilities($em, $versionGroups, 'wormadam-plant', ['wormadam-sandy', 'wormadam-sandy']);
        $this->saveSpecificAvailabilities($em, [$this->heartGoldSoulSilverVG, $this->platinumVG], 'shaymin-land', ['shaymin-sky']);
    }

    private function handleGen5(ObjectManager $em): void
    {
        $versionGroups = [$this->blackWhiteVG, $this->black2White2VG];

        $this->saveSpecificAvailabilities($em, $versionGroups, 'wormadam-plant', ['wormadam-sandy', 'wormadam-sandy']);
        $this->saveSpecificAvailabilities($em, $versionGroups, 'shaymin-land', ['shaymin-sky']);
        $this->saveSpecificAvailabilities($em, [$this->black2White2VG], 'kyurem', ['kyurem-black', 'kyurem-white'], true);
    }

    private function handleGen6(ObjectManager $em): void
    {
        $versionGroups = [$this->xyVG, $this->orasVG];
        $this->saveSpecificAvailabilities($em, $versionGroups, 'wormadam-plant', ['wormadam-sandy', 'wormadam-sandy']);
        $this->saveSpecificAvailabilities($em, $versionGroups, 'shaymin-land', ['shaymin-sky']);
        $this->saveSpecificAvailabilities($em, $versionGroups, 'kyurem', ['kyurem-black', 'kyurem-white'], true);
        $this->saveSpecificAvailabilities($em, $versionGroups, 'hoopa', ['hoopa-unbound']);
    }

    private function handleGen7(ObjectManager $em): void
    {
        $versionGroups = [$this->sunMoonVG, $this->ultraSunUltraMoonVG];

        $this->saveSpecificAvailabilities($em, $versionGroups, 'wormadam-plant', ['wormadam-sandy', 'wormadam-sandy']);
        $this->saveSpecificAvailabilities($em, $versionGroups, 'shaymin-land', ['shaymin-sky']);
        $this->saveSpecificAvailabilities($em, $versionGroups, 'kyurem', ['kyurem-black', 'kyurem-white'], true);
        $this->saveSpecificAvailabilities($em, $versionGroups, 'hoopa', ['hoopa-unbound']);
        $this->saveSpecificAvailabilities($em, [$this->sunMoonVG], 'lycanroc-midday', ['lycanroc-midnight']);
        $this->saveSpecificAvailabilities($em, [$this->ultraSunUltraMoonVG], 'lycanroc-midday', ['lycanroc-midnight', 'lycanroc-dusk']);
        $this->saveSpecificAvailabilities($em, [$this->ultraSunUltraMoonVG], 'necrozma', ['necrozma-dusk', 'necrozma-dawn','necrozma-ultra'], true);

    }

    private function handleGen8(ObjectManager $em): void
    {
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'kyurem', ['kyurem-black', 'kyurem-white'], true);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'lycanroc-midday',  ['lycanroc-midnight', 'lycanroc-dusk']);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'necrozma', ['necrozma-dusk', 'necrozma-dawn','necrozma-ultra'], true);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'toxtricity-amped',  ['toxtricity-low-key']);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'urshifu-single-strike',  ['urshifu-rapid-strike']);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'calyrex',  ['calyrex-ice-rider','calyrex-shadow-rider']);

    }

    public function getDependencies(): array
    {
        return [LoadBasicMoveAvailabilities::class];
    }

    private function getPokemon(ObjectManager $em, string $name): Pokemon
    {
        return $em->getRepository(Pokemon::class)->findOneBy(['name' => $name]);
    }

    private function getAvailabilityMove(ObjectManager $em, string $original): PokemonMoveAvailability
    {
        $pokemon = $em->getRepository(Pokemon::class)
        ->findOneBy(['name' => $original]);

        return $em->getRepository(PokemonMoveAvailability::class)
            ->findOneBy(['pokemon' => $pokemon]);
    }
}


