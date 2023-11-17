<?php

namespace App\Controller\Admin;

use App\Admin\Field\QRCodeField;
use App\Entity\URLShortener;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class URLShortenerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return URLShortener::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            UrlField::new('link', 'Lien'),
            TextField::new('slug', 'Slug')
                ->hideWhenCreating(),
            DateTimeField::new('createdAt', "Créé le")
                ->hideOnForm(),
            AssociationField::new('createdBy', 'Créé par')
                ->hideOnForm(),
            TextField::new('slug', 'QR Code')
                ->hideOnForm()
                ->setTemplatePath('admin/field/qr.html.twig')
                ->addCssClass('field-image')
                ->addJsFiles(Asset::fromEasyAdminAssetPackage('field-image.js'), Asset::fromEasyAdminAssetPackage('field-file-upload.js')),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['createdAt' => 'DESC']);
    }
}
