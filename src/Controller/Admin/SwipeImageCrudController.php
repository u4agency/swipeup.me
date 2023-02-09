<?php

namespace App\Controller\Admin;

use App\Entity\SwipeImage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SwipeImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SwipeImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = TextareaField::new('imageFile')
            ->setFormType(VichImageType::class)
            ->setLabel('Thumbnail');
        $imageName = ImageField::new('imageName')
            ->setBasePath('/assets/images/swipe_images');

        $fields = [
            TextField::new('id', 'Id')
                ->hideOnForm(),
            TextareaField::new('alt', 'Alt')
                ->hideOnForm(),
            AssociationField::new('author', 'Uploaded by'),
            BooleanField::new('isPublic', 'Public upload ?'),
            DateTimeField::new('uploadedAt', 'Uploaded at')
                ->hideOnForm(),
        ];

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $imageName;
        } else {
            $fields[] = $imageFile;
        }

        return $fields;
    }
}
