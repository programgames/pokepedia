<?php

namespace App\Controller\Admin;

use App\Entity\EvolutionChain;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EvolutionChainCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EvolutionChain::class;
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
