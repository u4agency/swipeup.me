<?php

namespace App\Form;

use App\Entity\SwipeUp;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\SlugType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SwipeUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $classes = "bg-white flex relative py-4 px-3 rounded-xl text-lg w-full overflow-x-hidden outline-none border-none";

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du SwipeUp',
                'attr' => [
                    'class' => $classes,
                ],
            ])
            ->add('slug', TextType::class, [
                'label' => 'Lien du SwipeUp',
                'attr' => [
                    'class' => 'text-lg w-full overflow-x-hidden outline-none mr-2 border-none p-0',
                    'placeholder' => 'votrenom',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du SwipeUp',
                'attr' => [
                    'class' => $classes,
                    'rows' => 4
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status du SwipeUp',
                'attr' => [
                    'class' => $classes,
                ],
                'choices' => [
                    'Public' => 'public',
                    'Non-répertorié' => 'unlisted',
                    'Privé' => 'private',
                ],
            ])
            ->add('logoFile', VichImageType::class, [
                'label' => 'Logo du SwipeUp',
                'attr' => [
                    'class' => 'text-white',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SwipeUp::class,
        ]);
    }
}
