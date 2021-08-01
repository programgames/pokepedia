<?php

namespace App\Controller\Admin;

use App\Entity\Machine;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MachineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Machine::class;
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
