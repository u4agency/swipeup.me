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
            AssociationField::new('swipeup', 'SwipeUp'),
            AssociationField::new('background', 'Image de fond'),
            AssociationField::new('widgetBody', 'Widget Body')
                ->setRequired(false),
            AssociationField::new('widgetFooter', 'Widget Footer')
                ->setRequired(false),
        ];
    }
}
