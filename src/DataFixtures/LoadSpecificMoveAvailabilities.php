<?php

    private function saveSpecificAvailabilities(ObjectManager $em, array $versionGroups, string $originalName, array $forms, bool $specificPageForForms = false)
    {

        foreach ($versionGroups as $versionGroup) {
            $original = $this->getAvailabilityMove($em, $originalName, $versionGroup);
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
        $this->saveSpecificAvailabilities($em, [$this->ultraSunUltraMoonVG], 'necrozma', ['necrozma-dusk', 'necrozma-dawn'], true);

    }

    private function handleGen8(ObjectManager $em): void
    {
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'kyurem', ['kyurem-black', 'kyurem-white'], true);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'lycanroc-midday',  ['lycanroc-midnight', 'lycanroc-dusk']);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'necrozma', ['necrozma-dusk', 'necrozma-dawn'], true);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'toxtricity-amped',  ['toxtricity-low-key']);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'urshifu-single-strike',  ['urshifu-rapid-strike']);
        $this->saveSpecificAvailabilities($em, [$this->swordShieldVG], 'calyrex',  ['calyrex-ice-rider','calyrex-shadow-rider'],true);

    }

    public function getDependencies(): array
    {
        return [LoadBasicMoveAvailabilities::class];
    }

    private function getPokemon(ObjectManager $em, string $name): Pokemon
    {
        return $em->getRepository(Pokemon::class)->findOneBy(['name' => $name]);
    }

    private function getAvailabilityMove(ObjectManager $em, string $original,VersionGroup $versionGroup): PokemonMoveAvailability
    {
        $pokemon = $em->getRepository(Pokemon::class)
        ->findOneBy(['name' => $original]);

        return $em->getRepository(PokemonMoveAvailability::class)
            ->findOneBy(
                [
                    'pokemon' => $pokemon,
                    'versionGroup' => $versionGroup
                ]
            );
    }
}


