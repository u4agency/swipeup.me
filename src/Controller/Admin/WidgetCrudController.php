<?php

namespace App\Controller\Admin;

use App\Entity\Widget;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WidgetCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Widget::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description'),
            ArrayField::new('display'),
            NumberField::new('price'),
            AssociationField::new('createdBy'),
            TextField::new('icon'),
        ];
    }
}
