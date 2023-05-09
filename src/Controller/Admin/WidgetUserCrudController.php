<?php

namespace App\Controller\Admin;

use App\Entity\WidgetUser;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class WidgetUserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WidgetUser::class;
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
