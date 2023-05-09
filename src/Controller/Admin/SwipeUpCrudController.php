<?php

namespace App\Controller\Admin;

use App\Entity\SwipeUp;
use App\Form\JsonCodeEditorType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SwipeUpCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SwipeUp::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = TextareaField::new('logoFile', 'Image')
            ->setFormType(VichImageType::class)
            ->setLabel('Thumbnail');
        $imageName = ImageField::new('logoName', 'Image')
            ->setBasePath('/assets/uploaded/swipe_logo');
        $imagePath = TextField::new('logoName', 'Image Path');

        $fields = [
            TextField::new('title', 'Title'),
            SlugField::new('slug', 'Slug')
                ->setTargetFieldName("title"),
            TextareaField::new('description', 'Description'),
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
            BooleanField::new('featuredSwipeUp', 'Display on homepage'),
            TextField::new('font', 'Font (from Google Fonts)'),
        ];

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $imageName;
            $fields[] = $imagePath;
        } else {
            $fields[] = $imageFile;
        }

        return $fields;
    }
}
