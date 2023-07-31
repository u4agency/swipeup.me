<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Swipe;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SwipeSectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'mapped' => false,
                'label' => "Titre de la section",
                'required' => false,
                'attr' => [
                    'placeholder' => 'Titre de la section',
                ],
            ])
            ->add('background', VichFileType::class, [
                'mapped' => false,
                'label' => "Fond de la section",
                'required' => true,
            ])
            ->add('buttonText', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Texte du bouton",
                'attr' => [
                    'placeholder' => 'Texte du bouton',
                ],
            ])
            ->add('buttonHref', UrlType::class, [
                'mapped' => false,
                'required' => false,
                'label' => "Lien du bouton",
                'attr' => [
                    'placeholder' => 'Lien du bouton',
                ],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
