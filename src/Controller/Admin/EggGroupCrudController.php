<?php

namespace App\Controller\Admin;

use App\Entity\EggGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EggGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EggGroup::class;
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
