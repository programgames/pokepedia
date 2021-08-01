<?php

namespace App\Controller\Admin;

use App\Entity\PokemonAvailability;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PokemonAvailabilityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PokemonAvailability::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
