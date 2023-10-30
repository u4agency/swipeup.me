<?php

namespace App\Form\Widgets;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailWidgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'required' => true,
                'attr' => [
                    "placeholder" => "Le texte de votre bouton",
                ],
                'data' => $options['autocomplete_data']['text'] ?? null,
            ])
            ->add('href', EmailType::class, [
                'required' => true,
                'attr' => [
                    "placeholder" => "L'adresse mail",
                ],
                'data' => $options['autocomplete_data']['href'] ?? null,
            ])
            ->add('textColor', ColorType::class, [
                'required' => false,
                'label' => "La couleur du texte de votre bouton",
                'attr' => [
                    "placeholder" => "La couleur du texte de votre bouton",
                ],
                'data' => $options['autocomplete_data']['textColor'] ?? null,
            ])
            ->add('colorType', ChoiceType::class, [
                'required' => true,
                'mapped' => false,
                'expanded' => true,
                'choices' => [
                    'Simple' => 'simple',
                    'Dégradé' => 'gradient',
                ],
                'data' => $options['autocomplete_data']['colorType'] ?? 'simple',
                'attr' => [
                    "value" => true,
                ],
            ])
            ->add('primaryColor', ColorType::class, [
                'required' => true,
                'label' => "La couleur primaire de votre bouton",
                'attr' => [
                    "placeholder" => "La couleur primaire de votre bouton",
                ],
                'data' => $options['autocomplete_data']['primaryColor'] ?? "#ffffff",
            ])
            ->add('secondaryColor', ColorType::class, [
                'required' => false,
                'label' => "La couleur secondaire de votre bouton",
                'attr' => [
                    "placeholder" => "La couleur secondaire de votre bouton",
                ],
                'data' => $options['autocomplete_data']['secondaryColor'] ?? null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autocomplete_data' => [],
        ]);
    }
}
