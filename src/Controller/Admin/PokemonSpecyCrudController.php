<?php

namespace App\Controller\Admin;

use App\Entity\PokemonSpecy;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PokemonSpecyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PokemonSpecy::class;
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
