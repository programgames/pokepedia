<?php


namespace App\DataFixtures;


use App\Entity\Pokemon;
use App\Entity\PokemonForm;
use App\Entity\PokemonFormName;
use App\Entity\VersionGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadUpdateGen8Forms extends Fixture implements AppFixtureInterface,DependentFixtureInterface
{

    public function getDependencies()
    {
        return [LoadPokemonFormName::class];
    }

    public function load(ObjectManager $manager)
    {
        $formNameRepository = $manager->getRepository(PokemonFormName::class);
        $formRepository = $manager->getRepository(PokemonForm::class);

        $swordShield = $manager->getRepository(VersionGroup::class)->findOneBy(['name' => 'sword-shield']);
        $alcreamy = $manager->getRepository(Pokemon::class)->findOneBy(['name' => 'alcremie']);

        $defautlAlcremie= $formRepository->findOneBy(['name' => 'alcremie']);
        $defautlAlcremie->setName('alcremie-vanilla-cream');
        $manager->persist($defautlAlcremie);

        foreach ($formNameRepository->findAll() as $formName) {
            $formName->setName(str_replace('’', '\'', $formName->getName()));
            $formName->setPokemonName(str_replace('’', '\'', $formName->getPokemonName()));
            $manager->persist($formName);
        }

        $order = 2;
        $newForms = [
            'alcremie-ruby-cream', 'ruby-cream',
            'alcremie-matcha-cream' => 'matcha-cream',
            'alcremie-mint-cream' => 'mint-cream',
            'alcremie-lemon-cream' => 'lemon-cream',
            'alcremie-salted-cream' => 'salted-cream',
            'alcremie-ruby-swirl' => 'ruby-swirl',
            'alcremie-caramel-swirl' => 'caramel-swirl',
            'alcremie-rainbow-swirl' => 'rainbow-swirl',
        ];

        foreach ($newForms as $name => $formName) {
            $formEntity = new PokemonForm();
            $formEntity->setName($name);
            $formEntity->setIsDefault(false);
            $formEntity->setIsBattleOnly(false);
            $formEntity->setIsMega(false);
            $formEntity->setFormName($formName);
            $formEntity->setVersionGroup($swordShield);
            $formEntity->setPokemon($alcreamy);
            $formEntity->setIsDefault(false);
            $formEntity->setFormOrder($order);
            $manager->persist($formEntity);
            $order++;
        }
        $manager->flush();

    }
}