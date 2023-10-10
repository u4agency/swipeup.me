<?php

namespace App\Controller\Admin;

use App\Entity\SwipeUp;
use App\Service\Status;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\BatchActionDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

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
                    'Public' => Status::PUBLIC,
                    'Unlisted' => Status::PENDING,
                    'Private' => Status::PRIVATE,

                    'Delete' => Status::DELETED,

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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->addBatchAction(Action::new('setDeletedStatus', 'Supprimer')
                ->linkToCrudAction('setDeletedStatus')
                ->addCssClass('btn btn-danger'))
            ->remove(Crud::PAGE_INDEX, Action::DELETE);
    }

    public function setDeletedStatus(BatchActionDto $batchActionDto): RedirectResponse
    {
        $className = $batchActionDto->getEntityFqcn();
        $entityManager = $this->container->get('doctrine')->getManagerForClass($className);
        foreach ($batchActionDto->getEntityIds() as $id) {
            $agence = $entityManager->find($className, $id);
            $agence->setStatus(Status::DELETED);
        }

        $entityManager->flush();

        return $this->redirect($batchActionDto->getReferrerUrl());
    }
}
