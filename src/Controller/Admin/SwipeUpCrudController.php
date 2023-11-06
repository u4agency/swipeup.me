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
        $imageFile = TextareaField::new('logoFile', 'Logo')
            ->setFormType(VichImageType::class)
            ->setLabel('Thumbnail');
        $imageName = ImageField::new('logoName', 'Lien du logo')
            ->setBasePath('/assets/uploaded/swipeup_logo');

        $fields = [
            TextField::new('title', 'Titre'),
            SlugField::new('slug', 'Slug')
                ->setTargetFieldName("title"),
            TextareaField::new('description', 'Description')
                ->hideOnIndex(),
            AssociationField::new('author', 'Créé par'),
            DateTimeField::new('createdAt', 'Créé à')
                ->hideOnForm(),
            DateTimeField::new('updatedAt', 'Mis à jour à')
                ->hideOnForm(),
            ChoiceField::new('status', 'Status')
                ->setChoices(array_flip(Status::READABLE_STATUS))
                ->renderExpanded(),
            BooleanField::new('featuredSwipeUp', 'A la une'),
            TextField::new('font', 'Police')
                ->hideOnIndex()
        ];

        $pageName == Crud::PAGE_INDEX || $pageName == Crud::PAGE_DETAIL ? $fields[] = $imageName : $fields[] = $imageFile;

        return $fields;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->addBatchAction(Action::new('setDeletedStatus', 'Supprimer')
                ->linkToCrudAction('setDeletedStatus')
                ->addCssClass('btn btn-danger'))
            ->update(Crud::PAGE_INDEX, Action::BATCH_DELETE, fn(Action $action) => $action
                ->setLabel("Supprimer définitivement")
                ->setIcon('fa fa-trash')
            );
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
