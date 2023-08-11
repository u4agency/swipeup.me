<?php

namespace App\Form\Widgets;

use App\Entity\WidgetSwipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonWidgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'required' => true,
                'attr' => [
                    "placeholder" => "Le texte de votre bouton",
                ],
            ])
            ->add('href', UrlType::class, [
                'required' => true,
                'attr' => [
                    "placeholder" => "Le lien de votre bouton",
                ],
            ])
            ->add('textColor', ColorType::class, [
                'required' => false,
                'label' => "La couleur du texte de votre bouton",
                'attr' => [
                    "placeholder" => "La couleur du texte de votre bouton",
                ],
            ])
            ->add('colorType', ChoiceType::class, [
                'required' => true,
                'mapped' => false,
                'expanded' => true,
                'choices' => [
                    'Simple' => 'simple',
                    'Dégradé' => 'gradient',
                ],
                'data' => 'simple',
                'attr' => [
                    "value" => true,
                ],
            ])
            ->add('primaryColor', ColorType::class, [
                'required' => true,
                'label' => "La couleur primaire de votre bouton",
                'attr' => [
                    "value" => "#ffffff",
                    "placeholder" => "La couleur primaire de votre bouton",
                ],
            ])
            ->add('secondaryColor', ColorType::class, [
                'required' => false,
                'label' => "La couleur secondaire de votre bouton",
                'attr' => [
                    "placeholder" => "La couleur secondaire de votre bouton",
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
