<?php

namespace App\Controller\Admin;

use App\Entity\Pokemon;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PokemonCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pokemon::class;
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
