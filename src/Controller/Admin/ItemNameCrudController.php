<?php

namespace App\Controller\Admin;

use App\Entity\ItemName;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ItemNameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ItemName::class;
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
