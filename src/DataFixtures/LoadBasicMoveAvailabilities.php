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

class LoadBasicMoveAvailabilities extends Fixture implements DependentFixtureInterface, AppFixtureInterface
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
        $this->handleGen8($em);
        $this->handleLGPE($em);
    }

    private function saveAvailabilities(ObjectManager $em, VersionGroup $versionGroup, $start, $end)
    {
        $pokemons = $em->getRepository(Pokemon::class)
            ->findDefaultPokemonsInNationalPokedex($start, $end);

        foreach ($pokemons as $pokemon) {
            $availability = new PokemonMoveAvailability();
            $availability->setVersionGroup($versionGroup);
            $availability->setPokemon($pokemon);
            $em->persist($availability);
        }
    }

    private function handleGen1(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->redBlueVg, 1, 151);
        $this->saveAvailabilities($em, $this->yellowVG, 1, 151);
    }

    private function handleGen2(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->crystalVG, 1, 251);
        $this->saveAvailabilities($em, $this->goldSilverVG, 1, 251);
    }

    private function handleGen3(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->fireRedLeafGreenVG, 1, 386);
        $this->saveAvailabilities($em, $this->rubySapphirVG, 1, 386);
        $this->saveAvailabilities($em, $this->emeraldVG, 1, 386);
    }

    private function handleGen4(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->diamondPearlVG, 1, 493);
        $this->saveAvailabilities($em, $this->platinumVG, 1, 493);
        $this->saveAvailabilities($em, $this->heartGoldSoulSilverVG, 1, 493);
    }

    private function handleGen5(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->blackWhiteVG, 1, 649);
        $this->saveAvailabilities($em, $this->black2White2VG, 1, 649);
    }

    private function handleGen6(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->xyVG, 1, 721);
        $this->saveAvailabilities($em, $this->orasVG, 1, 721);
    }

    private function handleGen7(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->ultraSunUltraMoonVG, 1, 807);
        $this->saveAvailabilities($em, $this->sunMoonVG, 1, 807);
        $pokemons = $em->getRepository(PokemonForm::class)
            ->findAlolaPokemons();
        foreach ($pokemons as $pokemon) {
            $availability = new PokemonMoveAvailability();
            $availability->setVersionGroup($this->sunMoonVG);
            $availability->setIsDefault(false);
            $availability->setPokemon($pokemon);
            $em->persist($availability);
        }
        foreach ($pokemons as $pokemon) {
            $availability = new PokemonMoveAvailability();
            $availability->setVersionGroup($this->ultraSunUltraMoonVG);
            $availability->setIsDefault(false);
            $availability->setPokemon($pokemon);
            $em->persist($availability);
        }
    }

    private function handleLGPE(ObjectManager $em): void
    {
        $this->saveAvailabilities($em, $this->lgpeVG, 1, 151);
        $this->saveAvailabilities($em, $this->lgpeVG, 808, 809);

        $pokemons = $em->getRepository(PokemonForm::class)
            ->findAlolaPokemons();
        foreach ($pokemons as $pokemon) {
            $availability = new PokemonMoveAvailability();
            $availability->setVersionGroup($this->lgpeVG);
            $availability->setIsDefault(false);
            $availability->setPokemon($pokemon);
            $em->persist($availability);
        }

    }

    private function handleGen8(ObjectManager $em): void
    {
        $pokemons = $this->findDefaultGen8Pokemons($em);

        foreach ($pokemons as $pokemon) {
            $availability = new PokemonMoveAvailability();
            $availability->setVersionGroup($this->swordShieldVG);
            $availability->setPokemon($pokemon);
            $em->persist($availability);
        }

        $pokemons = $em->getRepository(PokemonForm::class)
            ->findGen8AlolaPokemons();
        foreach ($pokemons as $pokemon) {
            $availability = new PokemonMoveAvailability();
            $availability->setVersionGroup($this->swordShieldVG);
            $availability->setIsDefault(false);
            $availability->setPokemon($pokemon);
            $em->persist($availability);
        }
        $pokemons = $em->getRepository(PokemonForm::class)
            ->findGalarPokemons();
        foreach ($pokemons as $pokemon) {
            $availability = new PokemonMoveAvailability();
            $availability->setVersionGroup($this->swordShieldVG);
            $availability->setIsDefault(false);
            $availability->setPokemon($pokemon);
            $em->persist($availability);
        }
    }

    public function getDependencies(): array
    {
        return [LoadVersionGroup::class, LoadPokemon::class, LoadPokemonForm::class];
    }

    private function findDefaultGen8Pokemons(ObjectManager $em)
    {
        $pokedex = $em->getRepository(Pokedex::class)
            ->findOneBy(['name' => 'national']);

        $specyIds = $em->getRepository(PokemonDexNumber::class)
            ->createQueryBuilder('d')
            ->select('s.id')
            ->leftJoin('d.pokemonSpecy', 's')
            ->leftJoin('d.pokedex', 'pokedex')
            ->andWhere('d.pokedexNumber >= :start and d.pokedexNumber <= :end')
            ->andWhere('d.pokedexNumber NOT IN (:exclude)')
            ->andWhere('pokedex.id = :pokedexId')
            ->setParameter('start', 1)
            ->setParameter('end', 898)
            ->setParameter('pokedexId', $pokedex->getId())
            ->setParameter('exclude', $this->getPokemonsNotInGen8())
            ->getQuery()
            ->getArrayResult();

        $specyIds = array_column($specyIds, "id");

        return $em->getRepository(Pokemon::class)
            ->createQueryBuilder('p')
            ->leftJoin('p.pokemonSpecy', 's')
            ->andWhere('p.isDefault = true')
            ->andWhere('s.id IN (:sids)')
            ->setParameter('sids', $specyIds)
            ->getQuery()
            ->getResult();
    }

    private function getPokemonsNotInGen8()
    {
        $pokemons = [13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 46, 47, 48, 49, 56, 57, 69, 70, 71, 74, 75, 76, 84, 85, 86, 87, 88, 89, 96, 97, 100
            , 101, 152, 153, 154, 155, 156, 157, 158, 159, 160, 161, 162, 165, 166, 167, 168, 179, 180, 181, 187, 188, 189, 190, 191, 192, 193, 198
            , 200, 201, 203, 204, 205, 207, 209, 210, 216, 217, 218, 219, 228, 229, 231, 232, 234, 235, 261, 262, 265, 266, 267, 268, 269, 276, 277
            , 283, 284, 285, 286, 287, 288, 289, 296, 297, 299, 300, 301, 307, 308, 311, 312, 313, 314, 316, 317, 322, 323, 325, 326, 327, 331, 332
            , 335, 336, 351, 352, 353, 354, 357, 358, 366, 367, 368, 370, 386, 387, 388, 389, 390, 391, 392, 393, 394, 395, 396, 397, 398, 399, 400
            , 401, 402, 408, 409, 410, 411, 412, 413, 414, 417, 418, 419, 424, 429, 430, 431, 432, 433, 441, 455, 456, 457, 469, 472, 476, 489, 490
            , 491, 492, 493, 495, 496, 497, 498, 499, 500, 501, 502, 503, 504, 505, 511, 512, 513, 514, 515, 516, 522, 523, 540, 541, 542, 580, 581
            , 585, 586, 594, 602, 603, 604, 648, 650, 651, 652, 653, 654, 655, 656, 657, 658, 664, 665, 666, 667, 668, 669, 670, 671, 672, 673, 676
            , 720, 731, 732, 733, 734, 735, 739, 740, 741, 774, 775, 779
        ];

        return $pokemons;
    }
}


