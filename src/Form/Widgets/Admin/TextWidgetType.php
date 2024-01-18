<?php

namespace App\Form\Widgets\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TextWidgetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'required' => true,
                'attr' => [
                    "placeholder" => "Le titre de votre section",
                    'data-introjs-target' => "element",
                    'data-title' => "Ajouter un texte à votre section",
                    'data-intro' => "Vous pouvez ajouter un texte à votre section.",
                    'data-step' => 2,
                ],
                'data' => $options['autocomplete_data']['text'] ?? null,
            ])
            ->add('color', ColorType::class, [
                'required' => false,
                'label' => "La couleur du texte de votre section",
                'attr' => [
                    "placeholder" => "La couleur du texte de votre section",
                    'data-introjs-target' => "element",
                    'data-title' => "Changer la couleur du texte",
                    'data-intro' => "Vous pouvez changer la couleur du texte de votre section.",
                    'data-step' => 3,
                ],
                'data' => $options['autocomplete_data']['color'] ?? null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'autocomplete_data' => [],
        ]);
    }
}
