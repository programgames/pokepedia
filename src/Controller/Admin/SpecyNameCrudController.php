<?php

namespace App\Controller\Admin;

use App\Entity\SpecyName;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SpecyNameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpecyName::class;
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
