<?php

namespace App\Controller\Admin;

use App\Entity\PokemonMove;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PokemonMoveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PokemonMove::class;
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
