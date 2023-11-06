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
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SwipeImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SwipeImage::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = TextareaField::new('backgroundFile', 'Fond')
            ->setFormType(VichImageType::class)
            ->setLabel('Thumbnail');
        $imageName = ImageField::new('backgroundName', 'Lien du fond')
            ->setBasePath('/assets/uploaded/swipe_images');
        $imagePath = TextField::new('backgroundName', 'Lien du fond')
            ->onlyOnDetail();

        $fields = [];

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $imageName;
            $fields[] = $imagePath;
        } else {
            $fields[] = $imageFile;
        }

        $fields += [
            TextField::new('id', 'Id')
                ->onlyOnDetail(),
            TextareaField::new('alt', "Alt de l'image")
                ->hideOnForm(),
            AssociationField::new('author', 'Upload par'),
            BooleanField::new('isPublic', 'Public'),
            DateTimeField::new('uploadedAt', 'Upload à')
                ->hideOnForm(),
        ];

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['uploadedAt' => 'DESC']);
    }
}
