<?php

namespace App\Controller\Admin;

use App\Entity\VersionGroup;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class VersionGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return VersionGroup::class;
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
