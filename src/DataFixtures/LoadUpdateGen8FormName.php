<?php


namespace App\DataFixtures;

use App\Entity\PokemonForm;
use App\Entity\PokemonFormName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LoadUpdateGen8FormName extends Fixture implements AppFixtureInterface, DependentFixtureInterface
{
    public function getDependencies()
    {
        return [LoadUpdateGen8Forms::class];
    }

    public function load(ObjectManager $manager)
    {

        $formNameRepository = $manager->getRepository(PokemonFormName::class);
        $formRepository = $manager->getRepository(PokemonForm::class);

        $file = __DIR__ . '/data/form_names.csv';
        if (($handle = fopen($file, 'rb')) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {

                $form = $formRepository->findOneBy(['name' => $data[0]]);
                $name = $formNameRepository->findOneBy(
                    [
                        'pokemonForm' => $form,
                        'language' => $data[1],
                        'name' => $data[2],
                        'pokemonName' => $data[3],
                    ]
                );

                if (!$name) {
                    $name = new PokemonFormName();
                    $name->setPokemonName($data[3]);
                    $name->setName($data[2]);
                    $name->setLanguage($data[1]);
                    $name->setPokemonForm($form);
                    $manager->persist($name);
                }
            }
            fclose($handle);
        }

        $manager->flush();
    }
}