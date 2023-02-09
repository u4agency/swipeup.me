<?php

namespace App\Controller\Admin;

use App\Entity\Swipe;
use App\Form\JsonCodeEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SwipeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Swipe::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Title'),
            TextField::new('slug', 'Slug'),
            TextareaField::new('description', 'Description'),
            CodeEditorField::new('swipe', 'Swipe Settings')
                ->setFormType(JsonCodeEditorType::class)
                ->hideOnIndex(),
            AssociationField::new('author', 'Created by'),
            DateTimeField::new('createdAt', 'Created at')
                ->hideOnForm(),
            DateTimeField::new('updatedAt', 'Updated at')
                ->hideOnForm(),
            ChoiceField::new('status', 'Status')
                ->setChoices([
                    'Public' => 'public',
                    'Unlisted' => 'unlisted',
                    'Private' => 'private',
                ])
                ->renderExpanded(),
            BooleanField::new('homepageDisplay', 'Display on homepage'),
            TextField::new('font', 'Font (from Google Fonts)'),
        ];
    }
}
