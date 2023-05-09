<?php

namespace App\Controller\Admin;

use App\Entity\Swipe;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class SwipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Swipe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('swipeup'),
            AssociationField::new('background'),
            AssociationField::new('widgetBody')
                ->setRequired(false),
            AssociationField::new('widgetFooter')
                ->setRequired(false),
        ];
    }
}
