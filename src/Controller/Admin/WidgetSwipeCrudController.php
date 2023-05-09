<?php

namespace App\Controller\Admin;

use App\Entity\WidgetSwipe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class WidgetSwipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WidgetSwipe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('widget'),
            AssociationField::new('widgetData'),
        ];
    }
}
