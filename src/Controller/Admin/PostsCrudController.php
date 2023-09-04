<?php

namespace App\Controller\Admin;

use App\Entity\Posts;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Posts::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $imageFile = TextareaField::new('imageFile')
            ->setFormType(VichImageType::class)
            ->setLabel('Thumbnail')
            ->hideOnIndex();
        $imageName = ImageField::new('imageName')
            ->hideOnIndex();

        $fields = [
            TextField::new('title', 'Titre de l\'article'),
            TextEditorField::new('content', 'Contenu de l\'article')
                ->hideOnIndex(),
            AssociationField::new('authors', 'Auteurs')
                ->hideOnIndex(),
            AssociationField::new('categories', 'Catégories')
                ->hideOnIndex(),
            ChoiceField::new('status', 'Status')
                ->setChoices([
                    'Publié' => 'published',
                    'En attente' => 'pending',
                    'Annulé' => 'cancelled',
                ])
                ->renderExpanded(),
            SlugField::new('slug', 'Slug')
                ->setTargetFieldName('title')
                ->setUnlockConfirmationMessage(
                    'It is highly recommended to use the automatic slugs, but you can customize them'
                )
                ->hideOnIndex(),
            DateTimeField::new('createdAt', "Créé le")
                ->hideOnForm(),
            TextField::new('seoTitle', 'Titre pour le SEO')
                ->hideOnIndex(),
            TextEditorField::new('seoContent', 'Contenu pour le SEO')
                ->setRequired(false)
                ->hideOnIndex(),
        ];

        if ($this->isGranted('ROLE_ADMIN')) {
            $fields[] = DateTimeField::new('createdAt', 'Créé le')
                ->onlyOnForms();
            $fields[] = DateTimeField::new('updatedAt', 'Mis à jour le')
                ->onlyOnForms();
        }

        if ($pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL) {
            $fields[] = $imageName;
        } else {
            $fields[] = $imageFile;
        }

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }
}
