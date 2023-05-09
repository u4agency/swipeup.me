<?php

namespace App\Controller\Admin;

use App\Entity\WidgetData;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WidgetDataCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WidgetData::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('widget'),
            AssociationField::new('widgetSwipe'),
            TextField::new('dataName'),
            TextField::new('dataValue'),
        ];
    }
}
