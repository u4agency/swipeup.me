<?php

namespace App\Controller\Admin;

use App\Entity\Pages;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class PagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pages::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre de l\'article'),
            TextEditorField::new('content', 'Contenu de l\'article')
                ->hideOnIndex()
                ->setFormType(CKEditorType::class),
            AssociationField::new('author', 'Créateur')
                ->hideOnForm(),
            SlugField::new('slug', 'Slug')
                ->setTargetFieldName('title')
                ->setUnlockConfirmationMessage(
                    'It is highly recommended to use the automatic slugs, but you can customize them'
                )
                ->hideOnIndex(),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }
}
